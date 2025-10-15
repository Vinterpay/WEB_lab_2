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
    document.querySelectorAll('input, select').forEach(el => {
      el.classList.add('highlight');
      setTimeout(() => el.classList.remove('highlight'), 3000);
    });
  }
}

resetReminder();

// Только alert перед отправкой — форма УЙДЁТ в process.php
document.getElementById("studentForm").addEventListener("submit", function() {
  const name = this.name.value;
  const age = this.age.value;
  const faculty = this.faculty.options[this.faculty.selectedIndex].text || this.faculty.value;
  const rules = this.rules.checked ? "Да" : "Нет";
  const studyForm = this.querySelector('input[name="study_form"]:checked')?.value || "Не выбрано";

  alert(`Вы отправляете:\nИмя: ${name}\nВозраст: ${age}\nФакультет: ${faculty}\nСогласие с правилами: ${rules}\nФорма обучения: ${studyForm}`);
  // НЕТ preventDefault() → отправка в process.php
});
