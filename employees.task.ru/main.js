let global_id = null;
async function getEmployees() {

   let res = await fetch(`http://127.0.0.1:8000/api/employees`);
   let employees = await res.json();
   employees = employees.data;

   document.querySelector('.employees-list').innerHTML = '';
   counter = 0
   employees.forEach(() => {
         document.querySelector('.employees-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <p class="card-text">Имя: ${employees[counter].name}</p>
               <p class="card-text">Должность: ${employees[counter].position}</p>
               <a href="#" class="card-link" onclick="deleteEmployee(${[employees[counter].id]})">Удалить</a>
               <div><a href="#" class="card-link" onclick="selectEmployee('${employees[counter].id}', '${employees[counter].name}','${employees[counter].position}')">Выбрать для редактирования</a></div>
            </div>
         </div>
      `;
      counter++;
   });
};


async function addEmployee() {
   const name = document.getElementById('add-name').value,
   position = document.getElementById('add-position').value;

   let formData = new FormData();
   formData.append('name', name);
   formData.append('position', position);

   const res = await fetch('http://127.0.0.1:8000/api/employees', {
      method: 'POST',
      body: formData
   });

   await getEmployees();
}

function selectEmployee(id, name, position) {
   global_id = id;
   document.getElementById('update-name').value = name;
   document.getElementById('update-position').value = position;
}


async function updateEmployee() {
   const name = document.getElementById('update-name').value,
   position = document.getElementById('update-position').value;

   let formData = new FormData();
   formData.append('_method', 'PUT');
   formData.append('name', name);
   formData.append('position', position);

   const res = await fetch(`http://127.0.0.1:8000/api/employees/${global_id}`, {
      method: 'POST',
      body: formData
   });
   await getEmployees();
}


async function deleteEmployee(id) {
   let formData = new FormData();
   formData.append('_method', 'DELETE');

   const res = await fetch(`http://127.0.0.1:8000/api/employees/${id}`, {
      method: "POST",
      body: formData
   });
   await getEmployees();
}

getEmployees();
