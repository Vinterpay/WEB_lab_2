let lastActivity = Date.now();
let reminderTimeout = null;

document.addEventListener('input', () => { lastActivity = Date.now(); resetReminder(); });
document.addEventListener('click', () => { lastActivity = Date.now(); resetReminder(); });

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

// Для AJAX отправки (если нужно оставить без перезагрузки)
document.getElementById("studentForm").addEventListener("submit", function(e) {
  // Если нужно оставить AJAX, раскомментируйте следующие строки:
  /*
  e.preventDefault();
  const formData = new FormData(this);
  let output = "<h2>Ваша регистрация:</h2>";

  const labels = {
    name: "Имя",
    age: "Возраст",
    faculty: "Факультет",
    study_form: "Форма обучения"
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
    if (key === "rules") {
      output += `<p><b>Согласие с правилами:</b> Да</p>`;
    } else if (key === "faculty") {
      output += `<p><b>${labels[key]}:</b> ${facultyMap[value]}</p>`;
    } else if (key === "study_form") {
      output += `<p><b>${labels[key]}:</b> ${studyFormMap[value]}</p>`;
    } else {
      output += `<p><b>${labels[key] || key}:</b> ${value}</p>`;
    }
  }

  if (!formData.has("rules")) {
    output += `<p><b>Согласие с правилами:</b> Нет</p>`;
  }

  document.getElementById("result").innerHTML = output;
  document.getElementById("result").style.display = "block";
  resetReminder();
  */
});