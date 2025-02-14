// export 
const paginate = {
    'paginationList': null,
    'pagination': 5,
    'prev': null,
    'next': null,
    'state': null,
    'params': null,
    'length': function () {
        return this.paginationList.length;
    },
    'setParams': function () {
        // console.log('setParams');
        this.params = this.makeList();
        // console.log('this.params');
        // console.log(this.params);
    },
    set PaginationList(param) {
        // console.log('paginationList');
        this.paginationList = param;
        // console.log('set paginationList');
        // console.log(this.paginationList);
    },
    set Prev(param) {
        // console.log('Prev');
        let pages = this.paginationList;
        this.prev = param;
        // console.log('this.prev');
        // console.log(this.prev);
        pages[0].children[0].setAttribute("value", this.prev);
        pages[0].children[0].setAttribute("onclick", "console.log(this.getAttribute('value'));paginate.cycle(this.getAttribute('value'), 'prev');");
    },
    set Next(param) {
        // console.log('Next');
        let pages = this.paginationList;
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
        let pages = this.paginationList;
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
        let pages = this.paginationList;
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
        let pages = this.paginationList;
        let length = this.length();
        pages[0].style = "display:inline;pointer-events:none;opacity:0.6;";
        pages[length - 1].style = "display:inline;pointer-events:none;opacity:0.6;";
    },
    'setup': function (ID) {
        // console.log('setup');
        this.PaginationList = (document.getElementById(ID)).children;
        // console.log('this.paginationList.length <= (this.pagination + 2)');
        // console.log(this.paginationList.length <= (this.pagination + 2));
        if (this.paginationList.length <= (this.pagination + 2)) {
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

const paginate_set_active = {
    'pagination_id' : null,
    'pagination_active_el' : null,
    'pagination_siblings_list' : null,
    set PaginationId(el_id){
        this.pagination_id = el_id;
    },
    set PaginationActiveEl(el_id){
        this.PaginationId = el_id;
        this.pagination_active_el = document.getElementById(this.pagination_id);
        this.pagination_active_el.classList.add('active');
        // console.log(this.pagination_active_el+' set to active');
    },
    set PaginationSiblingsList(parent_id){
        this.pagination_siblings_list = (document.getElementById(parent_id)).children;
        for(const el of this.pagination_siblings_list){
            if(el.classList.contains('active')){
                el.classList.remove('active');
            }
        }
    },
    'setup' : function(el_id, parent_id){
        this.PaginationSiblingsList = parent_id;
        this.PaginationActiveEl = el_id;
    }
};
