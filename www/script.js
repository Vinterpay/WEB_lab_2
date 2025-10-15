let lastActivity = Date.now();
let reminderTimeout = null;

document.addEventListener('input', () => {
  lastActivity = Date.now();
  resetReminder();
});

document.addEventListener('click', () => {
  lastActivity = Date.now();
  resetReminder();
});

function resetReminder() {
  if (reminderTimeout) clearTimeout(reminderTimeout);
  reminderTimeout = setTimeout(checkInactivity, 15000);
}

function checkInactivity() {
  const now = Date.now();
  if (now - lastActivity >= 15000) {
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(el => {
      el.classList.add('highlight');
      setTimeout(() => el.classList.remove('highlight'), 3000);
    });
  }
}

resetReminder();

document.getElementById("studentForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  let output = "<h2>Ваша регистрация:</h2>";

  const labels = {
    name: "Имя",
    age: "Возраст",
    faculty: "Факультет",
    study_form: "Форма обучения",
    rules: "Согласие с правилами"
  };

  const facultyMap = {
    it: "Информационные технологии",
    economics: "Экономика",
    medicine: "Медицина",
    law: "Юриспруденция"
  };

  const studyFormMap = {
    "full-time": "Очно",
    "part-time": "Заочно"
  };

  for (const [key, value] of formData.entries()) {
    let displayKey = labels[key] || key;
    let displayValue = value;

    if (key === "faculty") {
      displayValue = facultyMap[value] || value;
    } else if (key === "study_form") {
      displayValue = studyFormMap[value] || value;
    } else if (key === "rules") {
      displayValue = "Да";
    }

    output += `<p><b>${displayKey}:</b> ${displayValue}</p>`;
  }

  if (!formData.has("rules")) {
    output += `<p><b>${labels.rules}:</b> Нет</p>`;
  }

  document.getElementById("result").innerHTML = output;
  document.getElementById("result").style.display = "block";
  resetReminder();
});
