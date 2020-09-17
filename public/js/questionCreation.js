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
    checkedFirst();
});

function addAnswerForm($collectionHolder, $newLinkLi) {

    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index +1);
    let $newFormLi = $('<li class="liElements" style="display: flex; width: 100%; "><a class="deleteBtn"  style="width: 15%; color: #C82829" href="#">Delete</a></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    let ul = document.getElementsByClassName('answers');
    let li = ul[0].childNodes;
    li[index+1].setAttribute('id', index);
    let a = li[index+1].firstChild;
    a.addEventListener('click', function () {
        li[index+1].remove();
    })
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


