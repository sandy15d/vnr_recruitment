//Wizard Init

$("#wizard").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: '#title#',
    onFinished: function() {
        alert("Form successfully submitted!");
        location.reload();
    }
});

var wizard = $("#wizard")

//Form control

$('[data-step="next"]').on('click', function() {
    wizard.steps('next');
});

$('[data-step="previous"]').on('click', function() {
    wizard.steps('previous');
});

$('[data-step="finish"]').on('click', function() {
    wizard.steps('finish');
});

