"use strict";

let $collectionHolder;
let $addTagLink = $('<a href="#" class="add_tag_link">Add answer</a>');
let $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    $collectionHolder = $('ul.answers');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $addTagLink.on('click', function(e) {
        e.preventDefault();
        addAnswerForm($collectionHolder, $newLinkLi);
        changeRadio();
    });
});

function addAnswerForm($collectionHolder, $newLinkLi) {

    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
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