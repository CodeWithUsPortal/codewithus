<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teachers Notes</title>
</head>
<body>
Hello {{$teacher->user_name}},
<br>
<br>
Below is a note for your class {{$class->name}}, scheduled on {{$class->starts_at}}:
<br>
<br>
{{$notes}}
<br>
<br>
Thanks
<br>
<br>
CodeWithUs Team
</body>
</html>