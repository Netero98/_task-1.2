let global_id = null;
async function getEmployees() {

   let res = await fetch(`http://127.0.0.1:8000/api/employees`);
   let employees = await res.json();
   employees = employees['data'];

   document.querySelector('.employees-list').innerHTML = '';

   employees.forEach(() => {
         document.querySelector('.employees-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <p class="card-text">Имя: ${employees.name}</p>
               <p class="card-text">Должность: ${employees.position}</p>
               <a href="#" class="card-link" onclick="removeEmployee(${employees.id})">Удалить</a>
               <div><a href="#" class="card-link" onclick="selectEmployee('${employees.id}', '${employees.name}','${employees.position}')">Выбрать для редактирования</a></div>
            </div>
         </div>
      `
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

   const data = {
      name: name,
      position: position
   };

   const res = await fetch(`http://127.0.0.1:8000/api/employees/${global_id}`, {
      method: "PATCH",
      body: JSON.stringify(data)
   });
   await getEmployees();
}

async function removeEmployee(id) {
   const res = await fetch(`http://127.0.0.1:8000/api/employees/${id}`, {
      method: "DELETE"
   });
   await getEmployees();
}

getEmployees();
