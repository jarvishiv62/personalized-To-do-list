class PomodoroTimer {
    constructor() {
        this.timerInterval = null;
        this.currentSeconds = 0;
        this.isRunning = false;
        this.sessionId = null;

        this.initializeElements();
        this.initializeEventListeners();
        this.loadSessionStatus();
    }

    initializeElements() {
        this.timerDisplay = document.getElementById("timer");
        this.startBtn = document.getElementById("startBtn");
        this.pauseBtn = document.getElementById("pauseBtn");
        this.resetBtn = document.getElementById("resetBtn");
        this.taskSelect = document.getElementById("taskSelect");
        this.progressBar = document.getElementById("progressBar");
        this.sessionInfo = document.getElementById("sessionInfo");
        this.completeModal = new bootstrap.Modal(
            document.getElementById("sessionCompleteModal")
        );
        this.completeMessage = document.getElementById("completeMessage");
    }

    initializeEventListeners() {
        this.startBtn.addEventListener("click", () => this.startTimer());
        this.pauseBtn.addEventListener("click", () => this.pauseTimer());
        this.resetBtn.addEventListener("click", () => this.resetTimer());
        this.taskSelect.addEventListener("change", () =>
            this.updateLinkedTask()
        );
    }

    async loadSessionStatus() {
        try {
            const response = await fetch("/pomodoro/status");
            const data = await response.json();

            if (data.session) {
                this.updateTimerDisplay(data.session);
                if (data.session.status === "running") {
                    this.startTimerFromSession(data.session);
                }
            }
        } catch (error) {
            console.error("Failed to load session status:", error);
        }
    }

    async startTimer() {
        const taskId = this.taskSelect.value || null;

        try {
            const response = await fetch("/pomodoro/start", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({ task_id: taskId }),
            });

            const data = await response.json();

            if (data.success) {
                this.startTimerFromSession(data.session);
                this.showNotification(
                    "Timer started! Focus time begins now.",
                    "success"
                );
            }
        } catch (error) {
            console.error("Failed to start timer:", error);
            this.showNotification("Failed to start timer", "error");
        }
    }

    startTimerFromSession(session) {
        this.currentSeconds = session.remaining_seconds;
        this.sessionId = session.id;
        this.isRunning = true;

        this.updateButtons();
        this.startCountdown();
        this.updateSessionInfo(session);
    }

    startCountdown() {
        this.timerInterval = setInterval(() => {
            this.currentSeconds--;
            this.updateDisplay();
            this.updateProgressBar();

            // Update server every 10 seconds
            if (this.currentSeconds % 10 === 0) {
                this.updateServerTime();
            }

            if (this.currentSeconds <= 0) {
                this.handleTimerComplete();
            }
        }, 1000);
    }

    async pauseTimer() {
        clearInterval(this.timerInterval);
        this.isRunning = false;
        this.updateButtons();

        try {
            await fetch("/pomodoro/pause", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            this.showNotification("Timer paused", "info");
        } catch (error) {
            console.error("Failed to pause timer:", error);
        }
    }

    async resetTimer() {
        clearInterval(this.timerInterval);
        this.isRunning = false;

        try {
            const response = await fetch("/pomodoro/reset", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            const data = await response.json();

            if (data.success && data.session) {
                this.updateTimerDisplay(data.session);
                this.updateButtons();
                this.showNotification("Timer reset", "info");
            }
        } catch (error) {
            console.error("Failed to reset timer:", error);
        }
    }

    async updateServerTime() {
        try {
            await fetch("/pomodoro/update-time", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    remaining_seconds: this.currentSeconds,
                }),
            });
        } catch (error) {
            console.error("Failed to update server time:", error);
        }
    }

    handleTimerComplete() {
        clearInterval(this.timerInterval);
        this.isRunning = false;
        this.updateButtons();

        this.showSessionCompleteModal();
        this.showNotification("Session complete! Time for a break.", "success");

        // Reload session status to get updated break session
        setTimeout(() => this.loadSessionStatus(), 2000);
    }

    updateTimerDisplay(session) {
        this.currentSeconds = session.remaining_seconds;
        this.updateDisplay();
        this.updateProgressBar();
        this.updateSessionInfo(session);
    }

    updateDisplay() {
        const minutes = Math.floor(this.currentSeconds / 60);
        const seconds = this.currentSeconds % 60;
        this.timerDisplay.textContent = `${minutes
            .toString()
            .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
    }

    updateProgressBar() {
        const session = this.getCurrentSessionType();
        const totalSeconds = session === "focus" ? 1500 : 300; // 25min or 5min
        const percentage = (this.currentSeconds / totalSeconds) * 100;
        this.progressBar.style.width = `${percentage}%`;

        // Update color based on session type
        this.progressBar.className = `progress-bar bg-${
            session === "focus" ? "primary" : "success"
        }`;
    }

    updateSessionInfo(session) {
        if (session.status === "running" && session.started_at) {
            const startTime = new Date(session.started_at).toLocaleTimeString(
                "en-US",
                {
                    hour: "numeric",
                    minute: "2-digit",
                    hour12: true,
                }
            );
            this.sessionInfo.textContent = `${
                session.is_break ? "Break" : "Focus"
            } session running since ${startTime}`;
        } else {
            this.sessionInfo.textContent = "Ready to start a focus session";
        }
    }

    updateButtons() {
        this.startBtn.disabled = this.isRunning;
        this.pauseBtn.disabled = !this.isRunning;
    }

    getCurrentSessionType() {
        // This would ideally come from the server session data
        // For now, we'll determine based on duration
        return this.currentSeconds <= 300 ? "break" : "focus";
    }

    async updateLinkedTask() {
        if (this.isRunning) {
            // If timer is running, update the linked task on server
            try {
                await fetch("/pomodoro/start", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        task_id: this.taskSelect.value || null,
                    }),
                });
            } catch (error) {
                console.error("Failed to update linked task:", error);
            }
        }
    }

    showSessionCompleteModal() {
        const sessionType = this.getCurrentSessionType();
        const message =
            sessionType === "focus"
                ? "Focus session complete! Time for a 5-minute break."
                : "Break complete! Ready for another focus session?";

        this.completeMessage.textContent = message;
        this.completeModal.show();
    }

    showNotification(message, type = "info") {
        // Using Bootstrap toast or alert - you can implement based on your existing notification system
        console.log(`${type.toUpperCase()}: ${message}`);
        // For now, we'll use a simple alert
        alert(message);
    }
}

// Initialize timer when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    new PomodoroTimer();
});
