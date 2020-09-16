"use strict";

let $collectionHolder;
let $addTagLink = $('<a href="#" class="add_tag_link">Add answer</a>');
let $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    $collectionHolder = $('ul.answers');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    if (countAnswerForms()){
        addAnswerForm($collectionHolder, $newLinkLi);
        addAnswerForm($collectionHolder, $newLinkLi);
    }
    checkedFirst();
    changeRadio();
    $addTagLink.on('click', function(e) {
        e.preventDefault();
        if(countAnswers()){
            addAnswerForm($collectionHolder, $newLinkLi);
            changeRadio();
        } else {
            $addTagLink.remove();
            $collectionHolder.append('<a>Possible number of answers - 6</a>')
        }
    });
});

function addAnswerForm($collectionHolder, $newLinkLi) {

    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index +1);
    let $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

function changeRadio() {
    let radioBtns = document.getElementsByClassName('radioBtn');
    for (let i = 0; i < radioBtns.length; i++){
        radioBtns[i].addEventListener('click', function () {
            for (let j = 0; j < radioBtns.length; j++){
                radioBtns[j].name = 'question_form[answers][' + i + '][isTrue]';
            }
        })
    }
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

function countAnswers() {
    let liArr = document.getElementsByTagName('li');
    return liArr.length < 7;
}

function countAnswerForms() {
    let liArr = document.getElementsByTagName('li');
    return liArr.length === 1;
}

