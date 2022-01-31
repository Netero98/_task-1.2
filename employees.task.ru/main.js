let global_id = null;
async function getEmployees() {

   let res = await fetch(`https://meetings.api.ru/getEmployees`);
   let employees = await res.json();

   document.querySelector('.employees-list').innerHTML = '';

   employees.forEach((employee) => {
      document.querySelector('.employees-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <p class="card-text">Имя: ${employee.name}</p>
               <p class="card-text">Должность: ${employee.position}</p>
               <a href="#" class="card-link" onclick="removeEmployee(${employee.id})">Удалить</a>
               <div><a href="#" class="card-link" onclick="selectEmployee('${employee.id}', '${employee.name}','${employee.position}')">Выбрать для редактирования</a></div>
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

   const res = await fetch('https://meetings.api.ru/addEmployee', {
      method: 'POST',
      body: formData
   });

   const data = await res.json();

   if (data.status === true) {
      await getEmployees();
   }
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

   const res = await fetch(`https://meetings.api.ru/updateEmployee/${global_id}`, {
      method: "PATCH",
      body: JSON.stringify(data)
   });

   let resData = res.json();

   await getEmployees();
}

async function removeEmployee(id) {
   const res = await fetch(`https://meetings.api.ru/removeEmployee/${id}`, {
      method: "DELETE"
   });
   const data = await res.json();

   if(data.status === true) {
      await getEmployees();
   }
}

getEmployees();
