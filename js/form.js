import $ from "jquery"
import showModalProgress from "./modal-progress.js"

function finishSubmit(url, data)
{
    window.history.replaceState({}, "form submit", url)
    document.open()
    document.write(data)
    document.close()
}

function handleSubmit(event)
{
    const submitter = $(event.originalEvent.submitter)
    const form = $(event.currentTarget);
    const url = new URL(form.prop("action"));
    const formData = new FormData(form[0]);
    formData.append(submitter.prop("name"), submitter.prop("value"))
    const searchParams = new URLSearchParams(formData);

    const fetchOptions = {
    	method: form.prop("method").toUpperCase(),
    };

    if (form.prop("method").toLowerCase() === 'post') {
    	if (form.prop("enctype") === 'multipart/form-data') {
    		fetchOptions.body = formData;
            fetchOptions.mime = 'multipart/form-data'
    	} else {
    		fetchOptions.body = searchParams;
            fetchOptions.mime = 'application/x-www-form-urlencoded'
    	}
    } else {
    	url.search = searchParams;
    }

    var showPercent = false

    setTimeout(() => {showPercent = true}, 1000)
    
    const xhr = new XMLHttpRequest();
    xhr.open(fetchOptions.method, "" + url, true)
    xhr.upload.onprogress = function (ev) {
        showModalProgress(showPercent ? (ev.loaded / ev.total) : false)
    }
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            showModalProgress(null)
            if (xhr.status >= 200 && xhr.status < 300) {
                finishSubmit(url, xhr.responseText)
            }
        }
    }

    xhr.send(fetchOptions.body)

    event.preventDefault();
    return false
}

$(() => {
    $(".non-validated-action").click(function (ev){
        var el = $(this)
        while (el.length > 0 && el.prop("tagName").toLowerCase() != "form") {
            el = el.parent()
        }
        if (el.length == 0) {
            return
        }
        el.find("[required]").removeAttr("required")
    })
    $("form.with-progress").bind("submit", handleSubmit)
})
