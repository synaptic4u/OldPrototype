const w = 300;
const h = 75;
const y = 25;
const hgap = 50;
const vgap = 50;
const segment = 12.5;
const radius = 12.5;
let level1 = null;
let elements = null;
let hierachy = null;
let ctx = null;
let x = null;

const id = "hierachy-structure";
const hierachy_list = null;

const heirachy_map =  {
    'id' : null,
    'struct' : null,
    'anchors' : null,
    'list' : [],
    'subLoop' : function(div_id){
        let plist = [];
        for (let subindex = 0; subindex < this.anchors.length; subindex++) {
            let sublist = [];

            if(this.anchors[subindex].parentElement.parentElement.parentElement.parentElement.id == div_id){
                sublist['a_id'] = this.anchors[subindex].firstElementChild.innerText;
                sublist['div_id'] = (this.anchors[subindex].nextElementSibling) ? this.anchors[subindex].nextElementSibling.id : null;
                sublist['children'] = [];
                if(sublist['div_id'] != null){
                    sublist['children'] = this.subLoop(sublist['div_id']);
                }

                plist.push(sublist);
            };
            
        }
        return plist;
    },
    'init' : function(id){
        this.id = id;
        this.struct = document.getElementById(this.id);
        this.anchors = this.struct.getElementsByTagName("a");
        for (let index = 0; index < this.anchors.length; index++) {
            let sublist = [];
            if(this.anchors[index].parentElement.parentElement.parentElement.id == id){
                sublist['a_id'] = this.anchors[index].firstElementChild.innerText;
                sublist['div_id'] = (this.anchors[index].nextElementSibling) ? this.anchors[index].nextElementSibling.id : null;
                sublist['children'] = [];
                if(sublist['div_id'] != null){
                    sublist['children'] = this.subLoop(sublist['div_id']);
                }
                this.list[index] = sublist;
            }
        }

        return this.list;
    }
};

// hierachy_list = heirachy_map.init(id);

// ctx.scale(1.1, 1.1);
// hierachy.width = window.innerWidth;
// hierachy.height = window.innerHeight;
function setHierachySize() {
    hierachy = document.getElementById('hierachy');
    let width = hierachy.parentElement.offsetWidth - 2;
    hierachy.setAttribute("width", width);

    let side = document.getElementById('sidebar');
    let sideheight = side.offsetHeight;

    let headingOrganogram = document.getElementById('headingOrganogram');
    let orgheight = headingOrganogram.offsetHeight;

    let height = sideheight - (orgheight + orgheight + orgheight) - 84;
    hierachy.setAttribute("height", height);

    // console.log('sideheight: ' + sideheight);
    // console.log('orgheight: ' + orgheight);
    // console.log('height: ' + height);
    // console.log('width: ' + width);
}

function draw() 
{

    // console.log('Inside draw()');

    hierachy = document.getElementById('hierachy');
    ctx = hierachy.getContext('2d');

    level1 = hierachy.width / 2;
    x = level1 - w / 2;

    elements = [];

    ctx.clearRect(0, 0, hierachy.width, hierachy.height);
    hierachy.removeEventListener('click', canvasButtonClick);

    elements.push(new Rect('Emile De Wilde', w, h, x, y, 'liID'));
    elements.push(new Rect('The Avengers', w, h, elements[0].links.bottom.x + hgap, elements[0].links.bottom.y + vgap, 'liID'));
    elements.push(new Rect('The Empire', w, h, elements[0].links.bottom.x - w - hgap, elements[0].links.bottom.y + vgap, 'liID'));

    let pos = bottomRight(elements[0].links.bottom.x, elements[0].links.bottom.y);
    let pos2 = topLeft(elements[1].links.top.x, elements[1].links.top.y);
    lineFill(pos.x, pos.y, pos2.x, pos2.y);

    let pos4 = bottomLeft(elements[0].links.bottom.x, elements[0].links.bottom.y);
    let pos5 = topRight(elements[2].links.top.x, elements[2].links.top.y);
    lineFill(pos4.x, pos4.y, pos5.x, pos5.y);

    elements.forEach(function (rect) {
        rect.decorate();
    });

    hierachy.addEventListener('click', canvasButtonClick);

    hierachy.onmousemove = canvasButtonHover;
}

function connArrow(x, y) {

    let size = 6;
    ctx.beginPath();

    ctx.moveTo(x, y);
    ctx.lineTo(x + size, y - size);

    ctx.moveTo(x, y);
    ctx.lineTo(x - size, y - size);
    ctx.lineTo(x + size, y - size);

    ctx.fill();
    ctx.closePath();
}

function connStraight(x1, y1, x2, y2) {
    lineFill(x1, y1, x2, y2);
    connArrow(x2, y2);
}

function lineFill(x1, y1, x2, y2) {
    ctx.beginPath();

    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);

    ctx.stroke();
    ctx.closePath();
    // let pos = { 'x': x2, 'y': y2 };
    // return pos;
}

function topLeft(x, y) {
    connArrow(x, y);

    ctx.beginPath();

    ctx.moveTo(x, y);
    ctx.lineTo(x, y - segment);
    ctx.arc(x - segment, y - segment, radius, 360 * Math.PI / 180, 270 * Math.PI / 180, true);

    ctx.stroke();
    ctx.closePath();
    let pos = { 'x': x - segment, 'y': y - segment * 2 };
    return pos;
}

function topRight(x, y) {
    connArrow(x, y);

    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x, y - segment);
    ctx.arc(x + segment, y - segment, radius, 180 * Math.PI / 180, 270 * Math.PI / 180, false);
    ctx.stroke();
    ctx.closePath();

    let pos = {
        'x': x + segment,
        'y': y - segment * 2
    };

    return pos;
}

function bottomRight(x, y) {
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x, y + segment);
    ctx.arc(x + segment, y + segment, radius, 180 * Math.PI / 180, 90 * Math.PI / 180, true);
    ctx.stroke();
    ctx.closePath();

    let pos = { 'x': x + segment, 'y': y + segment * 2 };
    return pos;
}

function bottomLeft(x, y) {
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x, y + segment);
    ctx.arc(x - segment, y + segment, radius, 0 * Math.PI / 180, 90 * Math.PI / 180, false);
    ctx.stroke();
    ctx.closePath();

    let pos = {
        'x': x - segment,
        'y': y + segment * 2
    };

    return pos;
}

function roundedRect(ctx, x, y, width, height, radius, name = null, fill = null, shadow = null) {
    ctx.beginPath();
    if (shadow !== null) {
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 2;
        ctx.shadowBlur = 2;
        ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
    }

    ctx.moveTo(x, y + radius);
    ctx.lineTo(x, y + height - radius);
    ctx.arcTo(x, y + height, x + radius, y + height, radius);
    ctx.lineTo(x + width - radius, y + height);
    ctx.arcTo(x + width, y + height, x + width, y + height - radius, radius);
    ctx.lineTo(x + width, y + radius);
    ctx.arcTo(x + width, y, x + width - radius, y, radius);
    ctx.lineTo(x + radius, y);
    ctx.arcTo(x, y, x, y + radius, radius);
    if (fill !== null) {
        ctx.fillStyle = fill;
        ctx.fill();
    }
    ctx.stroke();

    if (name !== null) {
        ctx.font = '20px Arial';
        ctx.fillStyle = 'Black';
        let length = ctx.measureText(name).width;
        let margin = 5;
        ctx.fillText(name, x + (width / 2) - (length / 2), y + 20 + margin, length);

    }

    ctx.closePath();

    let links = {
        'top': { 'x': x + width / 2, 'y': y },
        'bottom': { 'x': x + width / 2, 'y': y + height },
        'start': { 'x': x, 'y': y + height / 2 },
        'end': { 'x': x + width, 'y': y + height / 2 }
    };

    return links;
}

function Rect(newName, width, height, x, y, id, parent = null) {
    return {
        'parent': null,
        'name': newName,
        'width': width,
        'height': height,
        'x': x,
        'y': y,
        'id': id,
        'links': roundedRect(ctx, x, y, width, height, 12.5, newName),
        /**
         * @param {{}} linkParam
         */
        set Links(linkParam) {
            this.links = linkParam;
        },
        'hover': function () {
            // console.log('This rect ' + this.id + ' is being hovered over');
        },
        'images': {
            'bottom': document.getElementById('svg-down'),
            'start': document.getElementById('svg-left'),
            'end': document.getElementById('svg-right')
        },
        'mount': function () {
            Object.keys(this.buttons).forEach(key => {

                this.buttons[key].name = this.name;
            });
        },
        'buttons': {
            'bottom': {
                'name': null,
                'x': null,
                'y': null,
                'clicked': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' has been clicked. Parent: ' + this.name);
                },
                'hover': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being hovered over. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, '#6c757d');
                    ctx.drawImage(this.image, this.x, this.y);
                },
                'clearHover': function () {
                    console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being cleared. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, null, null);
                    ctx.drawImage(this.image, this.x, this.y);
                }
            },
            'start': {
                'name': null,
                'x': null,
                'y': null,
                'clicked': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' has been clicked. Parent: ' + this.name);
                },
                'hover': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being hovered over. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, '#6c757d');
                    ctx.drawImage(this.image, this.x, this.y);
                },
                'clearHover': function () {
                    console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being cleared. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, null, null);
                    ctx.drawImage(this.image, this.x, this.y);
                }
            },
            'end': {
                'name': null,
                'image': null,
                'x': null,
                'y': null,
                'clicked': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' has been clicked. Parent: ' + this.name);
                },
                'hover': function () {
                    // console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being hovered over. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, '#6c757d');
                    ctx.drawImage(this.image, this.x, this.y);
                },
                'clearHover': function () {
                    console.log('This rect x: ' + this.x + ' y: ' + this.y + ' is being cleared. Parent: ' + this.name);
                    //  '#6c757d'
                    ctx.clearRect(this.x - 1, this.y - 1, 28, 28, 0);
                    roundedRect(ctx, this.x, this.y, 26, 26, 5, null, null, null);
                    ctx.drawImage(this.image, this.x, this.y);
                }
            }
        },
        'drawImages': function () {
            Object.keys(this.images).forEach(key => {
                ctx.clearRect(this.links[key].x - 13, this.links[key].y - 13, 26, 26, 0);
                roundedRect(ctx, this.links[key].x - 13, this.links[key].y - 13, 26, 26, 5);
                ctx.drawImage(this.images[key], this.links[key].x - 13, this.links[key].y - 13);
                this.buttons[key].x = this.links[key].x - 13;
                this.buttons[key].y = this.links[key].y - 13;
                this.buttons[key].image = this.images[key];
            });
        },
        'decorate': function () {
            this.mount();
            this.drawImages();
        }
    }
}

function canvasButtonClick(e) {
    var x = e.pageX - hierachy.offsetLeft;
    var y = e.pageY - hierachy.offsetTop;

    elements.forEach(function (element) {
        Object.keys(element.buttons).forEach(key => {
            if ((x > element.buttons[key].x && x < (element.buttons[key].x + 26)) && (y > element.buttons[key].y && y < (element.buttons[key].y + 26))) {
                // console.log('Inside : ' + element.buttons[key].name + ' Direction: ' + key);
                element.buttons[key].clicked();
            }
        });
    });
}

function canvasButtonHover(e) {
    var x = e.pageX - hierachy.offsetLeft;
    var y = e.pageY - hierachy.offsetTop;

    elements.forEach(function (element) {
        Object.keys(element.buttons).forEach(key => {
            if ((x > element.buttons[key].x && x < (element.buttons[key].x + 26)) && (y > element.buttons[key].y && y < (element.buttons[key].y + 26))) {
                // console.log('Inside : ' + element.buttons[key].name + ' Direction: ' + key);
                element.buttons[key].hover();
            }
            if (!(x > element.buttons[key].x && x < (element.buttons[key].x + 26)) && !(y > element.buttons[key].y && y < (element.buttons[key].y + 26))) {
                console.log('Inside : ' + element.buttons[key].name + ' Direction: ' + key);
                element.buttons[key].clearHover();
            }
        });
    });
}

/*
    elements.forEach(function (element) {
        if ((x > element.x && x < (element.x + element.width)) && (y > element.y && y < (element.y + element.height))) {
            console.log('Inside');
            console.log('x' + x);
            console.log('y' + y);
            console.log('element.x' + element.x + ' : ' + (element.x + element.width));
            console.log('element.y' + element.y + ' : ' + (element.y + element.height));
            element.clicked();
        }
    });

    // Get mouse position
    function getMousePos(hierachy, evt) {
        var rect = hierachy.getBoundingClientRect();
        return {
            x: evt.clientX - rect.left,
            y: evt.clientY - rect.top
        };
    };

    // Write mouse position
    function writeMessage(hierachy, m) {
        var ctx = hierachy.getContext('2d');
        ctx.clearRect(0, 0, hierachy.width, hierachy.height);
        ctx.font = '18pt Calibri';
        ctx.fillStyle = 'black';
        ctx.fillText(m, 10, 25);
    }
*/