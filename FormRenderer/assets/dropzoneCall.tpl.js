var jDropZone = $("#{id}");
Dropzone.autoDiscover = false;

var myDropzone = new Dropzone(jDropZone[0], conf);
var jForm = jDropZone.closest("form");

var idInput = "{id}-input";

myDropzone.on("success", function (file, r) {
    var jInput = jForm.find("#" + idInput);

    if (0 === jInput.length) {
        jInput = $('<input id="' + idInput + '" name="{name}">');
        jForm.append(jInput);

    }
    jInput.attr("value", r);
});

