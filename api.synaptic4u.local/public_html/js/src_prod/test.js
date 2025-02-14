
let top_level_list = (document.getElementById('hierachy-list')).children;

let list = [];
list = (document.getElementById('hierachy-list')).children;
for(const top_node of list){
    list[top_node.id] = (document.getElementById(top_node.id)).children;
    
    for(let cnt = 0;cnt < list[top_node.id].length;cnt++){
        // list[top_node.id][node.id] = [];
        list[top_node.id][node.id] = node.children;
    }
}
let list = {};
function buildList(id = null, list = null){
    if(list == null){
        list = (document.getElementById('hierachy-list')).children;
        for(const top_node of list){
            list[top_node.id] = (document.getElementById(top_node.id)).children;
        }
    }
    
}










let inputsArray = [];
let form = document.getElementById("User");
let inputs = form.getElementsByTagName("input");
let selects = form.getElementsByTagName("select");
let textareas = form.getElementsByTagName("textarea");

if (inputs.length > 0) {

    for (var x = 0; x < inputs.length; x++) {
        console.log(inputs[x].c);
        switch (inputs[x].type) {
            case 'text':
            case 'password':
            case 'email':
                inputsArray.push(strings.replace(inputs[x].value));
                break;
            case 'radio':
            case 'checkbox':
                (inputs[x].checked == true) ?
                    inputsArray.push(strings.replace(inputs[x].value)) : '';
                break;
        }
    }
}

if (selects.length > 0) {

    for (var x = 0; x < selects.length; x++) {

        for (var y = 0; y < selects[x].length; y++) {

            if ((selects[x][y].selected == true) && selects[x][y].value != '') {

                // console.log(selects[x][y].selected);console.log(selects[x][y].value);
                inputsArray.push(strings.replace(selects[x][y].value));
            }
        }
    }
}

// console.log(textareas.length);
if (textareas.length > 0) {

    for (var x = 0; x < textareas.length; x++) {

        inputsArray.push(strings.replace(textareas[x].value));
    }
}

let formchk;
let formreqs = form.querySelectorAll(".required");
if (formreqs.length > 0) {


    for (var x = 0; x < formreqs.length; x++) {

        console.log(formreqs[x]);
        switch (formreqs[x].type) {
            case 'text':
            case 'password':
            case 'email':
                formchk = (formreqs[x].value > '') ? x : 0;
                break;
            case 'radio':
            case 'checkbox':
                formchk = (formreqs[x].checked == true) ? x : 0;
                break;
        }

        switch (key) {
            case value:

                break;
            case value:

                break;
        }
    }
}

let popi = document.getElementById('popi_agreement');

if (popi == null) {
    console.log('popi == null inputsArray: ' + inputsArray);
    // send(elementID, m, c, inputsArray);
}
if (popi.checked == true) {
    console.log('popi.checked == true inputsArray: ' + inputsArray);
    // send(elementID, m, c, inputsArray);

} else {
    console.log('popi.checked == false inputsArray: ' + inputsArray);
    console.log("You cannot use this system without giving the software permssion to use your information.");
}
// END




const paginate = {
    'journalList': null,
    'pagination': 5,
    'prev': null,
    'next': null,
    'state': null,
    'params': null,
    'length': function () {
        return this.journalList.length;
    },
    'setParams': function () {
        this.params = this.makeList();
    },
    set JournalList(param) {
        this.journalList = param;
    },
    set Prev(param) {
        let pages = this.journalList;
        this.prev = param;
        pages[0].children[0].setAttribute("value", this.prev);
        pages[0].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'prev');");

    },
    set Next(param) {
        let pages = this.journalList;
        this.next = param;
        pages[pages.length - 1].children[0].setAttribute("value", this.next);
        pages[pages.length - 1].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'next');");

    },
    set State(param) {
        let params = this.params;
        let index = params.indexOf(param);
        console.log("set State(param)");
        console.log(params);
        console.log(param);
        console.log(index);
        if (index > -1 && index != params.length) {
            let paramArray = param.split(",");
            paramArray.forEach(function (item, index, arr) {
                arr[index] = parseInt(item);
            });
            this.state = paramArray;
        }
    },
    'getPages': function () {
        const pages = [];
        const length = this.length();
        for (let cnt = 1; cnt < length; cnt++) {
            if (((cnt % this.pagination) == 0) || (((cnt % this.pagination) / 1) == 1) || (cnt == length)) {
                if (((cnt % this.pagination) / 1) == 1) {
                    pages.push(cnt);
                }

                if ((cnt % this.pagination) == 0 || (cnt == length)) {
                    pages.push(cnt);
                }
            }
        }
        return pages;
    },
    'makeList': function () {
        let pages = this.getPages();
        let result = [];
        let length = pages.length;
        let limit = ((length % 2) == 1) ? (length - 1) : length;

        for (let cnt = 0; cnt <= limit; cnt += 2) {
            if (((cnt + 1) == length) && ((length % 2) == 1)) {
                result.push(([pages[cnt], pages[cnt]]).toString());
            } else {
                result.push(([pages[cnt], pages[cnt + 1]]).toString());
            }
        }
        return result;
    },
    'hideList': function (params) {
        let pages = this.journalList;
        let length = this.length();

        for (let cnt = 0; cnt < length; cnt++) {
            if (cnt >= params[0] && cnt <= params[1]) {
                pages[cnt].style = "display:inline;";
            } else {
                pages[cnt].style = "display:none;";
            }
        }
        pages[0].style = "display:inline;";
        pages[length - 1].style = "display:inline;";

    },
    'disable': function (params) {
        let pages = this.journalList;
        let length = this.length();

        if (params[0] == 1) {
            pages[0].style = "display:inline;pointer-events:none;opacity:0.6;";
            pages[length - 1].style = "display:inline;";
        }
        if (params[1] == (length - 2)) {
            pages[0].style = "display:inline;";
            pages[length - 1].style = "display:inline;pointer-events:none;opacity:0.6;";
        }
        if ((params[0] > 1) && (params[1] < (length - 2))) {
            pages[0].style = "display:inline;";
            pages[length - 1].style = "display:inline;";
        }
    },
    'setup': function (ID) {
        this.JournalList = (document.getElementById(ID)).children;
        this.setParams();
        let params = this.params;
        this.State = params[0];
        console.log('setup');
        console.log(this.state);
        console.log(params[0]);
        this.Prev = params[0];
        this.Next = params[1];
        this.hideList(this.state);
        this.disable(this.state);
    },
    'cycle': function (param = "", direction) {
        let state = this.state;
        let params = this.params;
        let index = params.indexOf(state.toString());

        if (direction == 'prev') {
            this.Prev = params[index - 1];
            this.Next = params[index - 1];
            this.State = params[index - 1];
        }
        if (direction == 'next') {
            this.Prev = params[index + 1];
            this.Next = params[index + 1];
            this.State = params[index + 1];
        }
        this.hideList(this.state);
        this.disable(this.state);
    }
};
// paginate.setup('journal_pagination');
// console.log(paginate);

const validate = {
    'form': null,
    set Form(ID) {
        this.form = document.getElementById(ID);
    },
};

let input = document.getElementById('login-email');
console.log(input.getAttribute('type'));

console.log(input);

if (input.getAttribute('required') == 'required') {
    if (input.getAttribute('type') == 'email') {
        if (input.value.match('@')) {
            console.log('yes');
        }
        console.log('Yes');
    }
    console.log('Yes');
} else {
    console.log('No');
}

console.log(input.value);


const callStack = {
    'list': [],
    setList(call) {
        this.list.push(call);
    },
    'cycle': function () {
        let list = this.list;
        for (let cnt = 0; cnt < list.length; cnt++) {
            // let item = this.list.shift();
            let item = callStack.list[cnt].substring(0, callStack.list[cnt].length);
            pause.short(item);
        }
    },
    'run': function () {
        this.cycle();
    }

}

callStack.setList("~load.message('<span class=\"p-1 alert sm alert-success fading\">Please add a section title to your journal.</span>')~");
callStack.setList(send("main_container", "a3e6bfbaf8b2d1cdf92bf83857fe1748c0f68de03d47600c327565e3be47933f4f78c9f3db2b612293a5982b4b97076676aff0e9cda1db19bc88644aa05503b4d7b7", "a3e7f79a2222c311aaf3748e792896911891d1ff255b600c32754a8b9457f887984ebad4f37612884d00e79730e7825dc1c477988f7a7240ef9fe6d87735c50713c4a9bc75ee06"));
callStack.setList(wipe.message());
callStack.setList(send("dash_show", "a3e8bfbaf8b2d1cdf92bf83857fe1748c0f68de03d47600c327565e3be47933f4f78c9f3db2b612293a5982b4b97826e593bccd546aea6eeb795080090cf17f6b8868c9e0e46", "a3e9f79a2222c311aaf3748e792896911891d1ff255b600c32754a8b9457f887984ebad4f37612884d00e79730e7901debac84ac42c76258fd5ac0bac5eb60e0c3f00bc6d42a6d0218964d"));

callStack.run();
console.log(callStack.list);
let str = callStack.list.shift();

console.log(str);
callStack.list[1];
console.log(callStack.list[1].substring(0, callStack.list[1].length - 1));


list = document.getElementsByTagName("script");

console.log(list.length);
list[list.length - 1].remove();
console.log(list.length);


list = document.getElementsByTagName("script");
size = list.length;
for (let x = size - 2; x > 7; x--) {
    if (list[x].id == 'dynamic') {
        list[x].remove();
    }
}

let x = 0;
for (const item of list) {
    x++;
    if (item.id == 'dynamic') {
        list[x].remove()
    }
}


let dynamic = document.createElement("script");
dynamic.setAttribute("id", "dynamicfun");
let calls = document.createTextNode('console.log("hello");');
dynamic.appendChild(calls);
document.body.appendChild(dynamic);

showActive(el){
    console.log(el);
}



let list2 = document.getElementsByClassName("dropdown-menu");
for (const el of list2) {
    if (el.classList.contains("show")) {
        console.log("TRUE!!!!!");
        el.setAttribute("style", "overflow: visible !important;");
    }
}
list.classList.contains("show");


const changeStyle = () => {
    let list = document.getElementsByClassName("dropdown-menu");
    for (const el of list) {
        if (el.classList.contains("show")) {
            console.log("TRUE!!!!!");
            el.style.overflow = "visible !important";
            el.style.position = "static";
        }
    }
}
// OLD PAGINATION
/*
        // const paginate = {
        // 	'journalList' : null,
        // 	'pagination' : 5,
        // 	'prev' : null,
        // 	'next' : null,
        // 	'state' : null,
        // 	'params' : null,
        // 	'length' : function () {
        // 		return this.journalList.length;
        // 	},
        // 	'setParams' : function(){
        // 		this.params = this.makeList();
        // 	},
        // 	set JournalList(param){
        // 		this.journalList = param;
        // 	},
        // 	set Prev(param){
        // 		let pages = this.journalList;
        // 		this.prev = param;
        // 		pages[0].children[0].setAttribute("value", this.prev);
        // 		pages[0].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'prev');");

        // 	},
        // 	set Next(param){
        // 		let pages = this.journalList;
        // 		this.next = param;
        // 		pages[pages.length-1].children[0].setAttribute("value", this.next);
        // 		pages[pages.length-1].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'next');");

        // 	},
        // 	set State(param){
        // 		let params = this.params;
        // 		let index = params.indexOf(param);
        // 		console.log("set State(param)");
        // 		console.log(params);
        // 		console.log(param);
        // 		console.log(index);
        // 		if(index > -1 && index != params.length){
        // 			let paramArray = param.split(",");
        // 			paramArray.forEach(function(item, index, arr){
        // 				arr[index] = parseInt(item);
        // 			});
        // 			this.state = paramArray;
        // 		}
        // 	},
        // 	'getPages' : function(){
        // 		const pages = [];
        // 		const length = this.length();
        // 		for (let cnt = 1; cnt < length; cnt++) {   
        // 			if ( ( (cnt % this.pagination) == 0) || ( ( (cnt % this.pagination) / 1) == 1) || (cnt == length ) ) {
        // 				if (((cnt % this.pagination) / 1) == 1) {
        // 					pages.push(cnt);
        // 				}
            	
        // 				if ((cnt % this.pagination) == 0 || (cnt == length)) {
        // 					pages.push(cnt);
        // 				}
        // 			}
        // 		}
        // 		return pages;
        // 	},
        // 	'makeList' : function (){
        // 		let pages = this.getPages();
        // 		let result = [];
        // 		let length = pages.length;
        // 		let limit = ((length % 2) == 1) ? (length-1) : length ;

        // 		for(let cnt = 0; cnt <= limit; cnt+=2 ){
        // 			if( ((cnt+1) == length) && ((length % 2) == 1) ){
        // 				result.push(([pages[cnt],pages[cnt]]).toString());
        // 			}else{
        // 				result.push(([pages[cnt],pages[cnt+1]]).toString());
        // 			}
        // 		}
        // 		return result;
        // 	},
        // 	'hideList' : function(params){
        // 		let pages = this.journalList;
        // 		let length = this.length();

        // 		for(let cnt = 0; cnt < length ; cnt++){
        // 			if(cnt >= params[0] && cnt <= params[1]){
        // 				pages[cnt].style = "display:inline;";
        // 			}else{
        // 				pages[cnt].style = "display:none;";
        // 			}
        // 		}
        // 		pages[0].style = "display:inline;";
        // 		pages[length-1].style = "display:inline;";

        // 	},
        // 	'disable' : function(params){
        // 		let pages = this.journalList;
        // 		let length = this.length();

        // 		if( params[0] == 1 ){
        // 			pages[0].style = "display:inline;pointer-events:none;opacity:0.6;";
        // 			pages[length-1].style = "display:inline;";
        // 		}
        // 		if( params[1] == (length-2) ){
        // 			pages[0].style = "display:inline;";
        // 			pages[length-1].style = "display:inline;pointer-events:none;opacity:0.6;"; 
        // 		}
        // 		if( ( params[0] > 1 ) && ( params[1] < (length-2) ) ){
        // 			pages[0].style = "display:inline;";
        // 			pages[length-1].style = "display:inline;"; 
        // 		}
        // 	},
        // 	'setup' : function(ID){
        // 		this.JournalList = (document.getElementById(ID)).children;
        // 		this.setParams();
        // 		let params = this.params;
        // 		this.State = params[0];
        // 		console.log('setup');
        // 		console.log(this.state);
        // 		console.log(params[0]);
        // 		this.Prev = params[0];
        // 		this.Next = params[1];
        // 		this.hideList(this.state);
        // 		this.disable(this.state);
        // 	},
        // 	'cycle' : function(param = "", direction) {
        // 		let state = this.state;
        // 		let params = this.params;
        // 		let index = params.indexOf(state.toString());

        // 		if(direction == 'prev'){
        // 			this.Prev = params[index-1];
        // 			this.Next = params[index-1];
        // 			this.State = params[index-1];
        // 		}
        // 		if(direction == 'next'){
        // 			this.Prev = params[index+1];
        // 			this.Next = params[index+1];
        // 			this.State = params[index+1];
        // 		}
        // 		this.hideList(this.state);
        // 		this.disable(this.state);
        // 	}
        // };
*/

// Check for the less then pagination - disable Prev & Next
// Account for a length less then pagination Number.
// Also on the set State Array, if there's less then 5 pages then there's a extra set in the array
const paginate = {
    'journalList': null,
    'pagination': 5,
    'prev': null,
    'next': null,
    'state': null,
    'params': null,
    'length': function () {
        return this.journalList.length;
    },
    'setParams': function () {
        // console.log('setParams');
        this.params = this.makeList();
        // console.log('this.params');
        // console.log(this.params);
    },
    set JournalList(param) {
        // console.log('JournalList');
        this.journalList = param;
        // console.log('set JournalList');
        // console.log(this.journalList);
    },
    set Prev(param) {
        // console.log('Prev');
        let pages = this.journalList;
        this.prev = param;
        // console.log('this.prev');
        // console.log(this.prev);
        pages[0].children[0].setAttribute("value", this.prev);
        pages[0].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'prev');");

    },
    set Next(param) {
        // console.log('Next');
        let pages = this.journalList;
        this.next = param;
        // console.log('this.next');
        // console.log(this.next);
        pages[pages.length - 1].children[0].setAttribute("value", this.next);
        pages[pages.length - 1].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'next');");

    },
    set State(param) {
        // console.log('State');
        let params = this.params;
        let index = params.indexOf(param);
        // console.log("set State(param)");
        // console.log(params);
        // console.log(param);
        // console.log(index);
        if (index > -1 && index != params.length) {
            let paramArray = param.split(",");
            paramArray.forEach(function (item, index, arr) {
                arr[index] = parseInt(item);
            });
            this.state = paramArray;
            // console.log('this.state');
            // console.log(this.state);
        }
    },
    'getPages': function () {
        // console.log('getPages');
        const pages = [];
        const length = this.length();
        // console.log('length');
        // console.log(length);
        for (let cnt = 1; cnt < length; cnt++) {
            // console.log('cnt');
            // console.log(cnt); 
            if (((cnt % this.pagination) == 0) || (((cnt % this.pagination)) == 1) || (cnt == (length - 1))) {
                if (((cnt % this.pagination)) == 1) {
                    pages.push(cnt);
                    // console.log('((cnt % this.pagination) ) == 1');
                    // console.log(cnt);
                }

                if ((cnt % this.pagination) == 0 || (cnt == (length - 1))) {
                    pages.push(cnt);
                    // console.log('(cnt % this.pagination) == 0 || (cnt == length)');
                    // console.log(cnt);
                }
            }
        }
        return pages;
    },
    'makeList': function () {
        // console.log('makeList');
        let pages = this.getPages();
        // console.log('pages');
        // console.log(pages);
        let result = [];
        let length = pages.length;
        // console.log('length');
        // console.log(length);
        let limit = ((length % 2) == 1) ? (length - 1) : length;
        // console.log('limit');
        // console.log(limit);

        for (let cnt = 0; cnt <= limit; cnt += 2) {
            if (((cnt + 1) == length) && ((length % 2) == 1)) {
                result.push(([pages[cnt], pages[cnt]]).toString());
            } else {
                result.push(([pages[cnt], pages[cnt + 1]]).toString());
            }
        }
        return result;
    },
    'hideList': function (params) {
        // console.log('hideList');
        let pages = this.journalList;
        let length = this.length();

        for (let cnt = 0; cnt < length; cnt++) {
            if (cnt >= params[0] && cnt <= params[1]) {
                pages[cnt].style = "display:inline;";
            } else {
                pages[cnt].style = "display:none;";
            }
        }
        pages[0].style = "display:inline;";
        pages[length - 1].style = "display:inline;";

    },
    'disable': function (params) {
        // console.log('disable');
        let pages = this.journalList;
        let length = this.length();

        if (params[0] == 1) {
            pages[0].style = "display:inline;pointer-events:none;opacity:0.6;";
            pages[length - 1].style = "display:inline;";
        }
        if (params[1] == (length - 2)) {
            pages[0].style = "display:inline;";
            pages[length - 1].style = "display:inline;pointer-events:none;opacity:0.6;";
        }
        if ((params[0] > 1) && (params[1] < (length - 2))) {
            pages[0].style = "display:inline;";
            pages[length - 1].style = "display:inline;";
        }
    },
    'shortList': function () {
        let pages = this.journalList;
        let length = this.length();
        pages[0].style = "display:inline;pointer-events:none;opacity:0.6;";
        pages[length - 1].style = "display:inline;pointer-events:none;opacity:0.6;";
    },
    'setup': function (ID) {
        // console.log('setup');
        this.JournalList = (document.getElementById(ID)).children;
        // console.log('journalList');
        // console.log(this.journalList);
        if (this.journalList.length <= (this.pagination + 2)) {
            this.shortList();
        } else {
            this.setParams();
            let params = this.params;
            // console.log('params');
            // console.log(this.params);
            this.State = params[0];
            // console.log('state');
            // console.log(this.state);
            // console.log('params[0]');
            // console.log(params[0]);
            this.Prev = params[0];
            this.Next = params[1];
            this.hideList(this.state);
            this.disable(this.state);
        }
    },
    'cycle': function (param = "", direction) {
        // console.log('cycle');
        let state = this.state;
        let params = this.params;
        let index = params.indexOf(state.toString());

        if (direction == 'prev') {
            this.Prev = params[index - 1];
            this.Next = params[index - 1];
            this.State = params[index - 1];
        }
        if (direction == 'next') {
            this.Prev = params[index + 1];
            this.Next = params[index + 1];
            this.State = params[index + 1];
        }
        this.hideList(this.state);
        this.disable(this.state);
    }
};



paginate.setup('journal_pagination');