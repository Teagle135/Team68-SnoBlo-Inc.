//SnoBlo Inc. Team 68 (Alvin Qin, Tony Ren, Eric Xu, Kuba Calik) - File authored by: Kuba Calik
//Set intial information
setYear = 2026;
setMonth = 3;
setDate = 1;

months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];


//Fetch DOM Elements
const current = document.getElementById("current");
const leftButton = document.getElementById("left-button");
const rightButton = document.getElementById("right-button");

const date = document.getElementById("date");

const statusBar = document.getElementById("status")


function createCalendar(year, month) {
    //Collect calendar information
    let startDate = new Date(year, month, 1).getDay();
    let monthDays = new Date(year, month + 1, 0).getDate();

    let table = '<table><tr><td>Sun</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td><td>Sat</td></tr><tr>';

    for (let i = 0; i < startDate; i++) {
        table += `<td></td>`;
    }

    for (let i = 1; i < monthDays; i++) {
        table += `<td class="number">${i}</td>`

        if ((i + startDate) % 7 === 0) {
            table += `</tr><tr>`
        }

    }

    table += `</tr></table>`
    return table;
};

function updateCalendar() {
    document.getElementById("date").innerHTML = createCalendar(setYear, setMonth);
    current.textContent = months[setMonth] + " " + setYear;
}

//Create button events
leftButton.addEventListener("click", function () {
    console.log(setMonth)
    if (setMonth == 0) {
        setMonth = 11;
        setYear -= 1;
    } else {
        setMonth -= 1;
    }
    updateCalendar()
})

rightButton.addEventListener("click", function () {
    console.log(setMonth)
    if (setMonth == 11) {
        setMonth = 0;
        setYear += 1;
    } else {
        setMonth += 1;
    }
    updateCalendar()
})

date.addEventListener("click", (event) => {
    if (event.target.classList.contains("number")) {

        const chosenDate = date.querySelector(".number.selected");
        if (chosenDate) {
            chosenDate.classList.remove("selected");
        }

        setDate = event.target.textContent;
        event.target.classList.add("selected")
    }
    const formattedMonth = String(setMonth + 1).padStart(2, '0');
    const formattedDay = String(setDate).padStart(2, '0');
    const sqlDate = `${setYear}-${formattedMonth}-${formattedDay}`;

    document.getElementById("selectedDate").value = sqlDate;
    console.log(selectedDate.value);
})


updateCalendar()