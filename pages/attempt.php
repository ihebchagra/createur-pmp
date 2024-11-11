<?php
// Get attempt
$stmt = $db->prepare('SELECT * FROM attempts WHERE attempt_id = ?');
$stmt->execute([$_GET['id']]);
$attempt = $stmt->fetch();

if (!$attempt) {
    header('Location: /dashboard');
    exit;
}

// Verify user has access to project
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$attempt['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: /dashboard');
    exit;
}

if ($attempt['locked']) {
    header('Location: /dashboard?t=locked');
    exit;
}

$stmt = $db->prepare('SELECT * FROM project_questions WHERE project_id = ?');
$stmt->execute([$attempt['project_id']]);
$questions = $stmt->fetchAll();

shuffle($questions);

$json_questions = htmlspecialchars(json_encode($questions));
$student_name = htmlspecialchars($attempt['student_name']);
$problem_text = htmlspecialchars($project['problem_text']);
$project_id = htmlspecialchars($attempt['project_id']);

// Rest of the HTML template remains the same
$content .= <<<HTML
    <main class="container">
    <h1>Étudiant : {$student_name}</h1>
    <h2>Énoncé : </h2>
    <p>{$problem_text}</p>
    <p><b>Quelle est votre conduite à tenir?</b></p>
    <form x-data="{ 
        questions: {$json_questions}, 
        revealed_questions: \$persist([]).as('revealed_questions-{$_GET['id']}').using(sessionStorage),
        total: \$persist(0).as('total-{$_GET['id']}').using(sessionStorage), 
        ended: \$persist(false).as('ended-{$_GET['id']}').using(sessionStorage),
        timerStarted: \$persist(false).as('timer-started-{$_GET['id']}').using(sessionStorage),
        timeLeft: \$persist(300).as('time-left-{$_GET['id']}').using(sessionStorage),
        startTimer() {
            this.timerStarted = true;
            const timer = setInterval(() => {
                if (this.timeLeft > 0 && !this.ended) {
                    this.timeLeft--;
                } else {
                    this.ended = true;
                    clearInterval(timer);
                }
            }, 1000);
        },
        formatTime() {
            const minutes = Math.floor(this.timeLeft / 60);
            const seconds = this.timeLeft % 60;
            return `\${minutes}:\${seconds.toString().padStart(2, '0')}`;
        }
    }" 
    x-init="if (timerStarted) startTimer()"
    @submit.prevent="
        modal.confirm('Êtes-vous sûr de vouloir terminer cette tentative ?','Confirmation').then(result=>{if (result) \$el.submit()})
    "
    method="post" action="/save-attempt">
        <input type="hidden" name="attempt_id" value="{$_GET['id']}">
        <input type="hidden" name="project_id" value="{$project_id}">
        <input type="hidden" name="total" x-model="total">
        <template x-for="(revealed_question,index) in revealed_questions" :key="index">
            <input type="hidden" :name="'questions[' + index +']'" x-model="revealed_question">
        </template>


        <div class="timer-section">
            <template x-if="!timerStarted && !ended">
                <div class="centered"><button type="button" @click="startTimer(); requestFullscreen();">Commencer le test</button></div>
            </template>
            <template x-if="timerStarted && !ended">
                <p><b>Temps Restant :</b> <span class="timer" x-text="formatTime()"></span></p>
            </template>
        </div>
        <template x-if="!ended && timerStarted">
            <h2>Propositions : </h2>
        </template>

        <template x-if="timerStarted">
            <div>
                <template x-for="(question, index) in questions" :key="index">
                    <article
                        class="proposition"
                        @click="if (!revealed && !ended && timerStarted) {
                            modal.confirm(question.question_text + '?','Confirmation')
                            .then(result=>{if (result) {
                                revealed = true;
                                revealed_questions.push(question.question_id);
                                if (question.solution_points == 'dead') {
                                    total = 0;
                                    ended = true;
                                } else {
                                    total += parseInt(question.solution_points);
                                }
                            }
                            });
                        }"
                        :class="{ revealed : revealed,
                                    dead : revealed && question.solution_points == 'dead',
                                    correct : revealed && question.solution_points != 'dead' && question.solution_points > 0,
                                    null : revealed && question.solution_points != 'dead' && question.solution_points == 0,
                                    incorrect : revealed && question.solution_points != 'dead' && question.solution_points < 0,
                                    ended : ended
                        }"
                        x-data="{ revealed: \$persist(false).as('revealed-' + question.question_id + '-{$_GET['id']}').using(sessionStorage) }"
     
                    >
                        <div><h5 x-text="question.question_text"></h5></div>
                        <div x-collapse x-show="revealed">
                            <p x-text="question.solution_text"></p>
                            <p class="solution_points" x-text="question.solution_points === '2' ? 'Proposition obligatoire :  +2 points' : 
                                       question.solution_points === '1' ? 'Proposition utile : +1 point' : 
                                       question.solution_points === '0' ? 'Proposition inutile : +0 points' :
                                       question.solution_points === 'dead' ? 'Proposition dangereuse, l\'épreuve est fini, votre score est 0' : 
                                       question.solution_points === '-2' ? 'Proposition dangereuse :  -2 points' : 
                                       question.solution_points === '-1' ? 'Proposition dangereuse : -1 point' : 'erreur'"></p>
                        </div>
                    </article>
                </template>
            </div>
        </template>
        <template x-if="ended">
                <h3>
                    L'épreuve est terminé! Votre score est de <span x-text="total"></span> points.
                </h3>
        </template>
        <div x-show="timerStarted" class="buttons">
            <button type="submit">Terminer la tentative</button>
        </div>
    </form>
    </main>
    <script>
    document.title = "Tentative - {$student_name}";
            
    function requestFullscreen() {
        const element = document.documentElement;
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) { // Firefox
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { // IE/Edge
            element.msRequestFullscreen();
        }
    }
    </script>
    HTML;
