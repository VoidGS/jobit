var applyModal = document.getElementById('applyModal')

applyModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    
    var idjob = button.getAttribute('data-bs-idjob')
    var titlejob = button.getAttribute('data-bs-titlejob')
    
    var jobTitle = applyModal.querySelector('#vagaTitulo')
    jobTitle.textContent = titlejob
    
    $("#applyJob").attr("href", '/user/apply?jobId=' + idjob)
})