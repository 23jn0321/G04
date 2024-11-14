<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>


$("#btn07").click(function(){
  Swal.fire({
    title: '好きなタイトルを入力',
    text: "好きなテキストを入力",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK'
  });
});
