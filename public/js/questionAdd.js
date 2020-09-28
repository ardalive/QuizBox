// let $collectionHolder;
// let $addAnswerLink = $('<a href="#" class="add_answer_link">Add a answer</a>');
// let $newLinkLi = $('<li></li>').append($addAnswerLink);

jQuery(document).ready(function() {

    $collectionHolder = $('ul.answers');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addAnswerLink.on('click', function(e) {
        e.preventDefault();
        addAnswerForm($collectionHolder, $newLinkLi);
    });

    $collectionHolder.find('li').each(function() {
        let deleteBtns = document.getElementsByClassName('btnDel');
        let forms = document.getElementsByTagName('fieldset');
        for (let i = 0; i < deleteBtns.length ; i++){
            deleteBtns[i].addEventListener('click', function () {
                forms[i].remove();
            })
        }
    });
    $collectionHolder.find('.radioBtn').each(function () {
        let radioBtns = document.getElementsByClassName('radioBtn');
        for (let i = 0; i < radioBtns.length; i++){
            radioBtns[i].addEventListener('click', function () {
                for (let j = 0; j < radioBtns.length; j++){
                    radioBtns[j].name = 'question_form[answers][' + i + '][isTrue]';
                }
            })
        }
    });
    checkedFirst();
});

function addAnswerForm($collectionHolder, $newLinkLi) {
    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    let $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    let deleteBtn = document.getElementById('question_form_answers_' + (index) + '_btnDelete');
    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault();
        $newFormLi.remove();
    })

    let btnRadio = document.getElementById('question_form_answers_' + (index) + '_isTrue');
    btnRadio.addEventListener('click', function () {
        let radioBtns = document.getElementsByClassName('radioBtn');
        for (let i = 0; i < radioBtns.length; i++){
            radioBtns[i].name = 'question_form[answers][' + index + '][isTrue]';
        }
    })
}

function checkedFirst() {
    let radioBtns = document.getElementsByClassName('radioBtn');
    for (let i = 0; i < radioBtns.length; i++) {
        if (radioBtns[i].hasAttribute('checked')){
            return;
        }
    }
    let radioBtn = document.getElementById('question_form_answers_0_isTrue');
    radioBtn.setAttribute('checked', true );
}