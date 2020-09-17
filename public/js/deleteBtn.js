let deleteBtn = document.getElementById('deleteBtnLink');
deleteBtn.addEventListener('click', function () {
    let content = document.getElementById('content');
    content.style.opacity = 0.1;

    let header = document.getElementsByClassName('navbar')[0];
    header.style.opacity = 0;
    header.style.position = 'none';
    header.style.position = 'absolute';

    let footer = document.getElementsByTagName('footer')[0];
    footer.style.opacity = 0;
    footer.style.display = 'none';

    let window_box = document.getElementById('window');
    window_box.style.display = 'block';
    setTimeout(()=> window_box.style.opacity = 1, 0);

    let blackSquare = document.getElementById('blackSquare');
    blackSquare.style.display = 'block';

})