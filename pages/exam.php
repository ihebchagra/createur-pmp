<?php
$stmt = $db->prepare('SELECT * FROM user_projects WHERE project_id = ? AND user_id = ?');
$stmt->execute([$_GET['project_id'], $auth->getUserId()]);
$project = $stmt->fetch();
// if project does not exist or user does not have access to it
if (!$project) {
    header('Location: /dashboard');
    exit;
}

$stmt = $db->prepare('SELECT * FROM project_questions WHERE project_id = ?');
$stmt->execute([$_GET['project_id']]);
$questions = $stmt->fetchAll();

$json_questions = htmlspecialchars(json_encode($questions));
$project_id = htmlspecialchars($project['project_id']);
$problem_text = htmlspecialchars($project['problem_text']);

$content = <<<HTML
<main class="container">
<h1>Modifier PMP</h1>
<form x-auto-animate method="post" action="/save-project" x-data="{ questions: {$json_questions} }">
    <input type="hidden" name="project_id" value="{$project_id}">
    <div>
        <label for="enonce">Énoncé:</label>
        <textarea rows=5 id="enonce" name="enonce" x-data x-autosize required>{$problem_text}</textarea>
    </div>
    <template x-for="(question, index) in questions" :key="index">
        <article>
            <label :for="'question_' + question.question_id">Proposition <span x-text="index + 1"></span>:</label>
            <input type="text" :id="'question_' + question.question_id" :name="'questions[' + question.question_id + '][text]'" x-model="question.question_text" required>
            <label :for="'solution_' + question.question_id">Réponse:</label>
            <textarea :id="'solution_' + question.question_id" :name="'questions[' + question.question_id + '][solution]'" x-model="question.solution_text" required></textarea>
            <label :for="'points_' + question.question_id">Points:</label>
            <select :id="'points_' + question.question_id" :name="'questions[' + question.question_id + '][points]'" :value="question.solution_points || '0'">
                <option value="-2">-2</option>
                <option value="-1">-1</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="dead">Finir Épreuve</option>
            </select>
            <div class="centered"><button class="secondary" type="button" @click="questions.splice(index, 1)">Supprimer cette Proposition</button></div>
        </article>
    </template>
    <div class="centered"><button type="button" @click="questions.push({question_id: Date.now(), question_text: '', solution_text: '', solution_points: '0'})">Ajouter une Proposition</button></div>
    <button type="submit">Sauvegarder</button>
</form>
</main>
<script>
        document.title = "Modifier PMP";
        </script>
HTML;
