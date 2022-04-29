<html>
<head>
<meta charset="utf-8">
<title>test ajax</title>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
//Ajax関数
$(() => poll());
async function poll() {

    var id = $('#id')[0].value;
    try {
        const response = await $.getJSON("ajax1.php?id=" + id);
        console.log(response);
        $('#msg').text(response);

    } catch (e) {
        console.error(e);
    } finally {
        setTimeout(poll, 1000);
    }
}

</script>
</head>
<body>
    <h2>test ajax json</h2>
    ID:<input type="text" id="id" size="10" maxlength="10" /><br />
    <p id="msg">テストメッセージ</p>
</body>
</html>