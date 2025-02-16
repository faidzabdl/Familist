<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Tugas</title>
</head>
<body>
    <h2>Reminder Tugas: {{ $task->title }}</h2>
    <p>Halo, {{ $task->user->name }}</p>
    <p>Jangan lupa! Tugas <b>{{ $task->name }}</b></p> 
    <p>harus selesai sebelum {{ $task->tenggat_waktu }}.</p>
    <p>Detail: {{ $task->deskripsi }}</p>
    <p>Salam hangat dari kami FamiList</p>
    <p>Terima kasih!</p>
</body>
</html>
