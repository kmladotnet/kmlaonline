//returns new row
function newRow(){
    let table = document.getElementById('list');
    table.insertRow(table.rows.length);
    let row = table.rows[table.rows.length - 1];
    for(let i = 0; i < table.rows[0].cells.length; i++){
        row.insertCell();
    }
    return table.rows[table.rows.length - 1];
}

//returns point value of article
function getPoints(article){
    for(let i = 0; i < articles[0].length; i++){
        if(articles[0][i] === article) return articles[1][i];
    }
}

//pass three strings, representing id's of radio. returns value of radio
function setGrade(r1, r2, r3){
    let grade;
    if(document.getElementById(r1).checked){
        grade = document.getElementById(r1).value;
    }
    else if(document.getElementById(r2).checked){
        grade = document.getElementById(r2).value;
    }
    else if(document.getElementById(r3).checked){
        grade = document.getElementById(r3).value;
    }
    return grade;
}

//returns index of row
function findPerson(person, grade){
    for(let index = 0; index < people.length; index++){
        if(people[index].name === person && people[index].grade === grade) return people[index].number;
    }
    return -1;
}

//returns index value of row to be inserted when person is already inside list
function getInsertIndex(index){
    let table = document.getElementById('list');
    let arr = table.rows;
    for(let i = 0; i < arr.length; i++){
        if(arr[i].cells.length === 8 && arr[i].cells[0].innerHTML == index){
            return i;
        }
    }
}

//place warning sign when at least one form is not filled
function warningText(){
    if(document.getElementsByTagName('h5').length < 1){ //limit warning sign count to 1
        alert('FILL IN ALL FORMS TO SUBMIT');
    }
}
//reset all article boxes to empty strings
function resetArticle(){
    let article = document.getElementsByTagName('datalist');
    for(let i = 0; i < article.length; i++){
        article[i].previousElementSibling.value = "";
    }
}
function isArticle(article){
    for(let i = 0; i < articles[0].length; i++){
        if(articles[0][i] === article) return true;
    }
    return false;
}
//submit function. Triggered on button click
function submitData(violation){
    let cellInput = [];
    let person, row, sum, check, index, insertIndex, table, arr;

    //sets information of person
    let number;
    let grade = setGrade('one', 'two', 'three');
    let name = document.getElementById('name').value;
    let date = document.getElementById('date').value;
    let accuser = document.getElementById('accuser').value;
    let article = violation;
    let points = getPoints(article);

    if(article != "" && !isArticle(article))
    {
        alert("Please enter valid article");
        return;
    }
    if(grade != undefined && name != "" && date != "" && accuser != "" && article != "" && isArticle(article))
    {
        //if person is not in list
        if(findPerson(name, grade) === -1){
            check = 0;

            person = new Person(people.length + 1, grade, name, points);
            //retrives new row
            row = newRow();
            //adds info to cell
            people.push(person);
            number = people.length;
            sum = person.sum;
        } else { //if person is already in list
            check = 1;

            //gets the no. property of person in the list
            index = findPerson(name, grade);

            //gets person object from people array
            person = people[index-1];

            //index number of new row
            insertIndex = getInsertIndex(index);

            //updates person object's sum point, article count
            person.sum += points;
            person.count += 1;
            sum = person.sum;

            //no. property of person in list
            number = index;

            //inserts row and following cells into table
            table = document.getElementById('list');
            table.insertRow(insertIndex);
            row = table.rows[insertIndex];
            for(let i = 0; i < table.rows[0].cells.length; i++){
                row.insertCell();
            }
        }

        //push data of cells into cellInput(array)
        cellInput.push(number); cellInput.push(grade); cellInput.push(name); cellInput.push(date); cellInput.push(accuser); cellInput.push(article); cellInput.push(points); cellInput.push(sum);

        //push cell information from cellInput, then sets style of row(cells)
        for(let i = 0; i < row.cells.length; i++){
            row.cells[i].innerHTML = cellInput[i];
            row.cells[i].className += "cell";
        }

        /*
        additional procedure required for person already in list
        deletes cells below the row that was inserted last
        then increases rowSpan of top row by 1
        */

        if(check){
            arr = document.getElementById('list').rows;

            //to merge cells using rowSpan, cells [0,1,2,7] below must be deleted first.
            arr[insertIndex + 1].deleteCell(0);
            arr[insertIndex + 1].deleteCell(0);
            arr[insertIndex + 1].deleteCell(0);
            arr[insertIndex + 1].deleteCell(4);

            for(let i = 0; i < overlap.length; i++){
                arr[getInsertIndex(index)].cells[overlap[i]].rowSpan = ""+person.count;
            }
        }
    } else {
        warningText();
    }
    sortList();
}
//sort people by article count
function sortPeople(){
    let i, j, k, tmp;
    for(i = 0; i < people.length - 1; i++){
        k = i;
        for(j = i; j < people.length; j++){
            if(people[k].count < people[j].count) k = j;
            else if(people[k].count === people[j].count){
                if(people[k].grade > people[j].grade) k = j;
                else if(people[k].grade === people[j].grade){
                    if(people[k].name > people[j].name) k = j;
                }
            }
        }
        tmp = people[k];
        people[k] = people[i];
        people[i] = tmp;
    }
}

//sorts list
function sortList(){
    sortPeople();

    let rowList = [];
    let table = document.getElementById('list');
    let len = table.rows.length;

    for(let i = 0; i < people.length; i++){
        let row = [];
        let index = getInsertIndex(findPerson(people[i].name, people[i].grade));

        for(let j = 0; j < people[i].count; j++){
            row.push(table.rows[index + j]);
        }
        row.reverse();
        rowList.push(row);
    }
    rowList.reverse();

    for(let i = 1; i < len; i++){
        table.deleteRow(1);
    }

    for(let i = 0; i < rowList.length; i++){
        for(let j = 0; j < rowList[i].length; j++){
            table.insertRow(1);
            cloneRow(1, rowList[i][j]);
        }
    }

    for(let i = 1; i < table.rows.length; i++){
        if(table.rows[i].cells.length === 8){
            table.rows[i].cells[0].innerHTML = getNumber(table.rows[i].cells[2].innerHTML)[0];
            for(let j = 0; j < overlap.length; j++){
                table.rows[i].cells[overlap[j]].rowSpan = getNumber(table.rows[i].cells[2].innerHTML)[1];
            }
        }
    }
}
//No property 바꾸는 거 해야 함!
//한꺼번에 No. property랑 rowspan 바꾸면 될 듯!

//clones all innerHTML and styles into new row
function cloneRow(index, row){
    let table = document.getElementById('list');
    for(let i = 0; i < row.cells.length; i++){
        table.rows[index].insertCell(0);
    }
    for(let i = 0; i < row.cells.length; i++){
        table.rows[1].cells[i].innerHTML = row.cells[i].innerHTML;
        table.rows[1].cells[i].className += "cell";
    }
}

//returns an array. first element is the no. property, second element is the number of articles
function getNumber(name){
    let ret = [];
    for(let i = 0; i < people.length; i++){
        if(people[i].name === name){
            people[i].number = i+1;
            ret.push((i+1)+"");
            ret.push(people[i].count+"");

            return ret;
        }
    }
}
