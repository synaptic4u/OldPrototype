const checkHierachyExists = () => {
    let ul = document.getElementById('hierachy-list');

    if (ul.children !== undefined) {
        document.getElementById('Hierachy').classList.add('hide');
    }
}
