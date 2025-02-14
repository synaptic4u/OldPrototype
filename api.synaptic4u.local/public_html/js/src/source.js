let timer;

const setStyle = (id, content) => document.getElementById(id).style = content;

const getHTML = (id) => document.getElementById(id).innerHTML;

const getElement = (id) => document.getElementById(id);

const setHTML = (id, content) => document.getElementById(id).innerHTML = content;

const appendHTML = (id, content) => document.getElementById(id).insertAdjacentHTML('beforeend', content);

const loadSpinner = (el_id) => {

    setHTML(el_id, '<div id="loading_spinner" class="position-absolute top-50 start-50 translate-middle-y" style="background-color:opaque;z-index:10011;"><div class="spinner-border text-dark" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    if(el_id.parentElement){
        setStyle(el_id.parentElement);
    }

};


const logout = (counter) => {
  timer = window.setTimeout(
    () => {
        window.location.href = 'logout.php?message=You have been inactive for longer then 25 minutes and have been logged out.';
    }, counter);
};

/**
 * message_mod
 * Initialise       : message_mod.init(title_message, content_message, type, color);
 *                  : If it's never been initialised, it will initialise.
 * Destruct         : message_mod.wipe();
 *                  : Destructor just cleans content and resets styling.
 * title_message    : Message Title
 * content_message  : Message Content
 * type             : info, success, danger
 * color            : white, dark [optional, default : dark]
 */
const message_mod = {
    'modal' : null,
    'title' : null,
    'content' : null,
    'type' : null,
    'color' : "dark",
    'init' : function(title_message, content_message, type, color = null){
        if(this.modal == null){
            this.modal = new bootstrap.Modal(document.getElementById('message_modal'), {
                keyboard: true
            });
        }

        this.title = document.getElementById('message_modal_title');
        this.title.innerText = title_message;

        this.content = document.getElementById('message_modal_content');
        this.content.innerText = content_message;

        this.type = type;
        this.color = color;
        
        switch(type){
            case "info":
                this.modal._element.childNodes[1].childNodes[1].classList.add("bg-info");
            break;
            case "success":
                this.modal._element.childNodes[1].childNodes[1].classList.add("bg-success");
            break;
            case "danger":
                this.modal._element.childNodes[1].childNodes[1].classList.add("bg-danger");
            break;
        }
        // if(type == "info"){
        //     this.modal._element.childNodes[1].childNodes[1].classList.add("bg-info");
        // }
        // if(type == "success"){
        //     this.modal._element.childNodes[1].childNodes[1].classList.add("bg-success");
        // }
        // if(type == "danger"){
        //     this.modal._element.childNodes[1].childNodes[1].classList.add("bg-danger");
        // }

        this.modal._element.childNodes[1].childNodes[1].classList.add("bg-opacity-50");

        if(color == "white"){
            this.modal._element.childNodes[1].childNodes[1].classList.add("text-white");
            this.modal._element.childNodes[1].childNodes[1].childNodes[1].setAttribute("style","border-bottom : 1px solid #dee2e6;");
            this.modal._element.childNodes[1].childNodes[1].childNodes[5].setAttribute("style","border-top : 1px solid #dee2e6;");
        }else{
            this.modal._element.childNodes[1].childNodes[1].classList.add("text-dark");
            this.modal._element.childNodes[1].childNodes[1].childNodes[1].setAttribute("style","border-bottom : 1px solid #6c757d;");
            this.modal._element.childNodes[1].childNodes[1].childNodes[5].setAttribute("style","border-top : 1px solid #6c757d;");
        }
        
        this.modal.show();
    },
    'wipe' : function(){
        setTimeout(function () {        
            this.modal.hide();

            this.title.innerText = '';
            this.content.innerText = '';

            if(this.type == "info"){
                this.modal._element.childNodes[1].childNodes[1].classList.remove("bg-info");
            }
            if(this.type == "success"){
                this.modal._element.childNodes[1].childNodes[1].classList.remove("bg-success");
            }
            if(this.type == "danger"){
                this.modal._element.childNodes[1].childNodes[1].classList.remove("bg-danger");
            }
            if(color == "white"){
                this.modal._element.childNodes[1].childNodes[1].classList.remove("text-white");
            }else{
                this.modal._element.childNodes[1].childNodes[1].classList.remove("text-dark");
            }

            this.modal._element.childNodes[1].childNodes[1].classList.remove("bg-opacity-50");

            this.modal._element.childNodes[1].childNodes[1].childNodes[1].setAttribute("style","border-bottom : 1px solid #dee2e6;");
            this.modal._element.childNodes[1].childNodes[1].childNodes[5].setAttribute("style","border-top : 1px solid #dee2e6;");
        }, 4000);
        
    },
    'toggle' : function(){
        if(this.modal != null){
            this.modal.toggle();
        }
    }
};

const toggleRowDisplay = (el, id) => {
    
    let hidden_row = document.getElementById(id);
    
    if(hidden_row.style.display == 'none'){
        // console.log('hidden_row.style.display == none');

        let hidden_rows = document.getElementsByClassName('app_roles_hidden_rows');
        for (const row of hidden_rows) {
            row.style.display = 'none';
        }

        let app_roles_parent_cells = document.getElementsByClassName('app_roles_parent_cells');
        for (const cell of app_roles_parent_cells) {
            cell.removeAttribute('style');
        }

        hidden_row.style.display = '';
        el.setAttribute("style", "font-weight:bold;");
    }else{
        // console.log('hidden_row.style.display == ""');
        hidden_row.style.display = 'none';
        el.removeAttribute('style');
    }
    
};

const userToggle = (inputid, labelid) => {
    let input = getElement(inputid);
    // console.log("Checked: "+input.checked == true);
    if(input.checked == true){
        setHTML(labelid, "? Personnel");
        // console.log("? Personnel"+input.checked);
    }else{
        setHTML(labelid, "? User");
        // console.log("? User"+input.checked);
    }
};

const userIncludeExclude = (inputid, labelid) => {
    let input = getElement(inputid);
    // console.log("Checked: "+input.checked == true);
    if(input.checked == true){
        setHTML(labelid, "? Exclude");
    }else{
        setHTML(labelid, "? Include");
    }
};

const scrollToEl = function (id) {
    setTimeout(function(){
        window.scrollTo({
            top : document.getElementById(id).offsetTop - (document.getElementById("main_container").offsetTop-6),
            left : 0,
            behavior :'smooth'
        });
    }, 2000);
};

const scrollToElement = function (id) {
    setTimeout(function(){
        window.scrollTo({
            top : document.getElementById(id).offsetTop - (document.getElementById("main_container").offsetTop-6),
            left : 0,
            behavior :'smooth'
        });
    }, 2000);
};

const scrollToTop = function(id){
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });
};

const fileLoader = {
    'result': null,
    'file': null,
    'profileEl': null,
    'imgEl': null,
    'reader': new FileReader(),
    'getFile': function (id, target, target2 = null) {
        this.file = document.getElementById(id).files[0];
        this.imgEl = document.getElementById(target);

        this.profileEl = (target2 != null) ? document.getElementById(target2) : null;
        // this.profileEl = document.getElementById(target2);
        // console.log(this.file);
        if (this.file) {
            this.reader.onload = (loadedEvent) => {
                this.result = loadedEvent.target.result;
                // console.log('Result1:', this.result);
                this.imgEl.src = this.result;
                this.profileEl.src = this.result;
            }
            this.reader.readAsDataURL(this.file);
        }
    },
};

const setClassInnerText = (classname, content) => {
    let htmllist = document.getElementsByClassName(classname);
    for (let x = 0; x < htmllist.length; x++) {
        htmllist[x].innerText = content;
    }
};

const dynMain = () => {
    let main = document.getElementById("main");
    main.removeAttribute('style');
};

const hideToast = (id) => {
    let toast = document.getElementById(id);
    toast.classList.remove('show');
    toast.classList.add('hide');
};

const showToast = (id) => {
    let toast = document.getElementById(id);
    toast.classList.remove('hide');
    toast.classList.add('show');
};

const clickButton = (id) => {
    (document.getElementById(id)).click();
};

const openAccordion = (id) => {
    let sidebar = document.getElementById('hierachy-button');
    sidebar.click();
    let accordion = document.getElementById(id);
    if (accordion.getAttribute('aria-expanded') == 'false') {
        accordion.click();
    }

};

const buttonManageState = (el) => {
    // console.log(el);
    // console.log(el.parentElement.nextElementSibling);
    showActive(el.parentElement.nextElementSibling);
    if (el.parentElement.nextElementSibling.getAttribute("aria-expanded") === "true") {
        el.parentElement.nextElementSibling.click();
    }
    // if (el.parentElement.previousElementSibling.getAttribute("aria-expanded") === "true") {
    //     el.parentElement.previousElementSibling.click();
    // }

};

const manageState = (el) => {
    // console.log("aria-expanded attribute:");
    // console.log(el.getAttribute("aria-expanded"));
    // console.log(el);
    if (el.getAttribute("aria-expanded") === "true") {

        let list = null;
        if (el.classList.contains("hierachy-link")) {
            list = document.getElementsByClassName("ariatoggler hierachy-link");
        }
        if (el.classList.contains("sidebar-link")) {
            list = document.getElementsByClassName("ariatoggler sidebar-link");
        }

        for (const toggler of list) {
            toggler.setAttribute("aria-expanded", "false");
            toggler.nextElementSibling.classList.remove("show");
            toggler.nextElementSibling.classList.remove("collapser");
        }

        let sister = (el.parentElement.parentElement.parentElement.parentElement.previousElementSibling.classList.contains("ariatoggler")) ?
            el.parentElement.parentElement.parentElement.parentElement.previousElementSibling : null;
        // console.log(sister);
        if (sister !== null) {
            let bigsister = (sister.parentElement.parentElement.parentElement.parentElement.previousElementSibling.classList.contains("ariatoggler")) ?
                sister.parentElement.parentElement.parentElement.parentElement.previousElementSibling : null;

            sister.nextElementSibling.classList.add("show");
            sister.setAttribute("aria-expanded", "true");

            if (bigsister !== null) {
                // console.log(bigsister);
                bigsister.nextElementSibling.classList.add("show");
                bigsister.setAttribute("aria-expanded", "true");
            }
        }
        el.nextElementSibling.classList.add("show");
        el.setAttribute("aria-expanded", "true");
    }
}

const navTogglerCheck = () => {

    let buttons = document.getElementsByClassName('navbar-toggler');

    let sidebar = document.getElementById('sidebar');
    if (sidebar.getAttribute('aria-modal') === 'true') {
        if (buttons[1].getAttribute('aria-expanded') === 'true') {
            buttons[1].click();
        }
    }
};

const setProfileMenu = {
    'image': function (id, url, alt) {
        let img = document.getElementById(id);
        img.src = url;
        img.alt = alt;
    },
    'user': function (id, name) {
        let el = document.getElementById(id);

        el.innerHTML = name;
    },
    'profile': function (id, call) {
        let el = document.getElementById(id);

        el.setAttribute("onclick", call);
    },
    'logout': function (id, call) {
        let el = document.getElementById(id);

        el.setAttribute("onclick", call);
    }
};

const changeStyle = () => {
    let list = document.getElementsByClassName("dropdown-menu");
    for (const el of list) {
        if (el.classList.contains("show")) {
            // console.log("TRUE!!!!!");
            el.style.overflow = "visible !important";
            el.style.position = "static";
        }
    }
};

const classToggle = {
    'add': function (id, name) {
        let el = document.getElementById(id);

        el.classList.add(name);
    },
    'remove': function (id, name) {
        let el = document.getElementById(id);

        el.classList.remove(name);
    }
};

const wipe = {
    'message': () => {
        //	clear the contents of #message

        let message = getElement('message');
        // console.log(message.id);

        message.style.opacity = 1;

        setTimeout(function () {
            // fade.out('message');
            message_mod.wipe = '';
        }, 8000);
    },
    'script': () => {
        //	Remove unwanted script tags

        let list = document.getElementsByTagName("script");
        let size = list.length;
        for (let x = size - 3; x > 7; x--) {
            if (list[x].id == 'dynamic') {
                list[x].remove();
            }
        }
    },
    'form': (id) => {
        // console.log("Inside wipe.form(el): id: " + id);
        let formDiv = document.getElementById(id);
        formDiv.innerHTML = '';
    }
};

const strings = {

    'replace': (string) => {
        // Replaces app unfriendly characters

        let str = string.replace(/,/g, '__:comma:__');
        str = str.replace(/'/g, '__:sngquote:__');
        str = str.replace(/"/g, '__:dblquote:__');
        str = str.replace(/</g, '__:openbracket:__');
        str = str.replace(/>/g, '__:closebracket:__');
        str = str.replace(/\r/g, '__:return:__');
        str = str.replace(/\n/g, '__:newline:__');
        str = str.replace(/onload=/g, '__:onloadequals:__');
        // str = string;
        return str.replace(/\+/g, '__plus__').replace(/\//g, '__slash__').replace(/\=/g, '__equals__');
    },

    'return': (string) => {
        // Restores app unfriendly characters

        let str = string.replace(/__:comma:__/g, ',');
        str = str.replace(/__:sngquote:__/g, '\'');
        str = str.replace(/__:dblquote:__/g, '"');
        str = str.replace(/__:openbracket:__/g, '<');
        str = str.replace(/__:closebracket:__/g, '>');
        str = str.replace(/__:return:__/g, '\r');
        str = str.replace(/__:newline:__/g, '\n');
        str = str.replace(/__:onloadequals:__/g, 'onload=');
        // str = string;
        return str.replace(/__plus__/g,'\+').replace(/__slash__/g,'\/').replace(/__equals__/g,'\=');
    }
};

const load = {
    'message': (message) => {
        setHTML('message', message);
    }
};

const pause = {
    'short': (call) => {
        setTimeout(function () {
            (call)();
        }, 3000);
    },
    'long': (call) => {
        setTimeout(function () {
            call;
        }, 10000);
    }
};

const showActive = (el) => {

    let list = document.getElementsByClassName("nav-link");

    for (const anchor of list) {
        anchor.classList.remove("active");
        // anchor.classList.remove("disabled");
    }

    // console.log(list);
    // console.log(el);
    el.classList.add("active");
    // el.classList.add("disabled");
};

window.addEventListener('keydown', function (e) {
    if (e.keyIdentifier == 'U+000A' || e.keyIdentifier == 'Enter' || e.keyCode == 13) {
        if (e.target.nodeName == 'INPUT' && e.target.type == 'text') {
            e.preventDefault();

            return false;
        }
    }
}, true);

const scroller = {
    'top': (id) => {
        let scroller = document.getElementById(id);
        scroller.scrollTop = 0;
    },
    'bottom': (id) => {
        let scroller = document.getElementById(id);
        scroller.scrollTop = scroller.scrollHeight;
    }

};