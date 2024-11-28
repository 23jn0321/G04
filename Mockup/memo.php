<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>

  <p>3を選択した時だけリンクを表示</p>

  <select name="select" id="select">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3" selected>3</option>
  </select>

  <div id="link">
    <a href="https://qiita.com/">3の時だけ表示するリンク</a>
  </div>

<script>

  $(document).ready(function() {
  if ($('#select option:selected').text() === '3') {
    $('#link').show();
  }
  $('#select').on('change', function () {
    if ($('#select option:selected').text() === '3') {
      $('#link').show();
    } else {
      $('#link').hide();
    }
  });
});

</script>