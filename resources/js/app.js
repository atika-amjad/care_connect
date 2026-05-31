import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.meditationHub = (steps, config) => ({
    // Mode: 'breathing' | 'guided'
    mode: 'breathing',
    filter: 'all',
    exercises: config.exercises,
    labels: config.labels,

    selectedExercise: null,
    guidedStepIndex: 0,
    guidedComplete: false,

    active: false,
    stepIndex: 0,
    phase: 'idle',
    title: steps.startTitle,
    subtitle: steps.techniqueHint || steps.technique,
    buttonLabel: steps.startLabel,
    timer: null,
    tickTimer: null,
    secondsLeft: 0,
    phaseDuration: 0,
    cycles: 0,
    totalSeconds: 0,
    ringProgress: 0,
    phaseDurations: { inhale: 4, hold: 7, exhale: 8 },
    phaseLabels: steps.phaseLabels || {},

    phaseLabel() {
        return this.phaseLabels[this.phase] || '';
    },

    formatTime(secs) {
        const m = Math.floor(secs / 60).toString().padStart(2, '0');
        const s = (secs % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    stepLabel() {
        const total = this.guidedSteps().length;
        const current = Math.min(this.guidedStepIndex + 1, total);
        return (this.labels.stepOf || 'Step :current of :total')
            .replace(':current', current)
            .replace(':total', total);
    },

    guidedSteps() {
        return this.selectedExercise?.guided_steps || [];
    },

    ringScale() {
        if (!this.active || this.phase === 'idle') return 1;
        if (this.mode === 'guided') return 1.05;
        if (this.phase === 'inhale') return 1 + (0.18 * (1 - this.secondsLeft / this.phaseDuration));
        if (this.phase === 'hold') return 1.18;
        if (this.phase === 'exhale') return 1.18 - (0.18 * (1 - this.secondsLeft / this.phaseDuration));
        return 1;
    },

    heroIcon() {
        if (this.guidedComplete) return '✨';
        if (this.mode === 'guided' && this.selectedExercise) return this.selectedExercise.icon;
        return '🌬️';
    },

    filteredExercises() {
        if (this.filter === 'all') return this.exercises;
        return this.exercises.filter(e => e.level === this.filter);
    },

    selectExercise(exercise) {
        this.stopAll();
        this.mode = 'guided';
        this.selectedExercise = exercise;
        this.guidedStepIndex = 0;
        this.guidedComplete = false;
        this.active = false;
        this.phase = 'idle';
        this.title = exercise.name;
        this.subtitle = this.labels.readyToBegin || exercise.description;
        this.buttonLabel = '▶ ' + (this.labels.startSession || 'Begin Session');
        this.ringProgress = 0;
        this.secondsLeft = 0;
        document.getElementById('meditation-hero')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },

    backToBreathing() {
        this.stopAll();
        this.mode = 'breathing';
        this.selectedExercise = null;
        this.guidedComplete = false;
        this.resetBreathing();
    },

    heroAction() {
        if (this.mode === 'guided') {
            if (this.guidedComplete) {
                this.backToBreathing();
                return;
            }
            if (this.phase === 'paused') {
                this.resumeSession();
            } else if (!this.active) {
                this.startGuided();
            } else {
                this.pauseSession();
            }
            return;
        }
        this.toggleBreathing();
    },

    toggleBreathing() {
        this.active = !this.active;
        if (this.active) {
            this.buttonLabel = steps.pauseLabel;
            if (this.phase === 'idle' || this.phase === 'paused') {
                this.runBreathStep();
            } else {
                this.startTick();
            }
        } else {
            this.buttonLabel = steps.resumeLabel;
            this.phase = 'paused';
            clearTimeout(this.timer);
            clearInterval(this.tickTimer);
            this.title = steps.pausedLabel;
        }
    },

    startGuided() {
        this.active = true;
        this.guidedComplete = false;
        this.guidedStepIndex = 0;
        this.totalSeconds = 0;
        this.buttonLabel = steps.pauseLabel;
        this.runGuidedStep();
    },

    runGuidedStep() {
        if (!this.active || this.mode !== 'guided') return;
        const guided = this.guidedSteps();
        if (this.guidedStepIndex >= guided.length) {
            this.completeGuided();
            return;
        }
        const step = guided[this.guidedStepIndex];
        this.phase = 'guided';
        this.title = step.title;
        this.subtitle = step.text;
        this.phaseDuration = step.seconds;
        this.secondsLeft = step.seconds;
        this.ringProgress = 0;
        this.startTick();
        this.timer = setTimeout(() => {
            this.guidedStepIndex++;
            this.runGuidedStep();
        }, step.seconds * 1000);
    },

    completeGuided() {
        this.active = false;
        clearTimeout(this.timer);
        clearInterval(this.tickTimer);
        this.guidedComplete = true;
        this.phase = 'idle';
        this.title = this.labels.sessionComplete || 'Session complete!';
        this.subtitle = this.selectedExercise?.name || '';
        this.buttonLabel = this.labels.backToBreathing || 'Back';
        this.ringProgress = 100;
        this.$refs.completeForm?.submit();
    },

    pauseSession() {
        this.active = false;
        clearTimeout(this.timer);
        clearInterval(this.tickTimer);
        this.phase = 'paused';
        this.buttonLabel = steps.resumeLabel;
    },

    resumeSession() {
        this.active = true;
        this.buttonLabel = steps.pauseLabel;
        if (this.mode === 'guided') {
            this.phase = 'guided';
            this.startTick();
            this.timer = setTimeout(() => {
                this.guidedStepIndex++;
                this.runGuidedStep();
            }, Math.max(this.secondsLeft, 1) * 1000);
        } else {
            if (this.phase === 'paused') {
                this.runBreathStep();
            } else {
                this.startTick();
            }
        }
    },

    startTick() {
        clearInterval(this.tickTimer);
        this.tickTimer = setInterval(() => {
            if (!this.active) return;
            this.totalSeconds++;
            if (this.secondsLeft > 0) {
                this.secondsLeft--;
                this.ringProgress = 100 - ((this.secondsLeft / this.phaseDuration) * 100);
            }
        }, 1000);
    },

    runBreathStep() {
        if (!this.active) return;
        const keys = ['inhale', 'hold', 'exhale'];
        const key = keys[this.stepIndex % 3];
        this.phase = key;
        this.title = steps.steps[key][0];
        this.subtitle = steps.steps[key][1];
        this.phaseDuration = this.phaseDurations[key];
        this.secondsLeft = this.phaseDuration;
        this.ringProgress = 0;
        if (key === 'exhale') this.cycles++;
        this.startTick();
        this.stepIndex++;
        this.timer = setTimeout(() => this.runBreathStep(), this.phaseDuration * 1000);
    },

    resetAll() {
        if (this.mode === 'guided' && this.selectedExercise) {
            this.stopAll();
            this.guidedStepIndex = 0;
            this.guidedComplete = false;
            this.title = this.selectedExercise.name;
            this.subtitle = this.labels.readyToBegin || this.selectedExercise.description;
            this.buttonLabel = '▶ ' + (this.labels.startSession || 'Begin Session');
        } else {
            this.resetBreathing();
        }
    },

    resetBreathing() {
        this.active = false;
        clearTimeout(this.timer);
        clearInterval(this.tickTimer);
        this.stepIndex = 0;
        this.phase = 'idle';
        this.cycles = 0;
        this.totalSeconds = 0;
        this.secondsLeft = 0;
        this.ringProgress = 0;
        this.title = steps.startTitle;
        this.subtitle = steps.techniqueHint || steps.technique;
        this.buttonLabel = steps.startLabel;
    },

    stopAll() {
        this.active = false;
        clearTimeout(this.timer);
        clearInterval(this.tickTimer);
    },

    isSelected(exercise) {
        return this.selectedExercise?.slug === exercise.slug;
    },
});

Alpine.data('moodForm', () => ({
    selected: null,
    tags: [],

    pick(mood) {
        this.selected = mood;
    },

    toggleTag(tag) {
        if (this.tags.includes(tag)) {
            this.tags = this.tags.filter(t => t !== tag);
        } else {
            this.tags.push(tag);
        }
    },

    isSelected(mood) {
        return this.selected?.score === mood.score;
    },

    hasTag(tag) {
        return this.tags.includes(tag);
    },
}));

Alpine.data('journalForm', (wordLabel) => ({
    content: '',
    wordCount: 0,

    updateCount() {
        const words = this.content.trim().split(/\s+/).filter(Boolean);
        this.wordCount = this.content.trim() ? words.length : 0;
    },

    addPrompt(text) {
        this.content += text;
        this.updateCount();
    },

    label() {
        return `${this.wordCount} ${wordLabel}`;
    },
}));

Alpine.start();
