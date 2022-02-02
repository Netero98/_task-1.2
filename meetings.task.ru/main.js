let global_id = null;
async function getMeetings() {
   // эти данные нужно сделать функционально из даты начала и конца периода, за который 
   // нужна информация и преобразовать в универсальный формат unix, так как участники собрания могут находиться по всему миру и участвовать онлайн.

   let dayStarts = 1643580000;  // 8:00 31 января 2022 года (utc+10)
   let dayEnds = 1643616000;    // 18:00 31 января 2022 года (utc+10)

   let res = await fetch(`http://127.0.0.1:8000/api/getMeetings/${dayStarts}/${dayEnds}`);
   let meetings = await res.json();
   meetings = meetings.data

   document.querySelector('.meetings-list').innerHTML = '';
   let counter = 0;
   meetings.forEach(() => {
      document.querySelector('.meetings-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <h5 class="card-title">${meetings[counter].name}</h5>
               <p class="card-text">Начало: ${meetings[counter].startstamp}</p>
               <p class="card-text">Конец: ${meetings[counter].endstamp}</p>
               <a href="#" class="card-link" onclick="deleteMeeting(${meetings[counter].id})">Удалить</a>
               <div><a href="#" class="card-link" onclick="selectMeeting('${meetings[counter].id}', '${meetings[counter].name}','${meetings[counter].startstamp}', '${meetings[counter].endstamp}')">Выбрать для редактирования</a></div>
            </div>
         </div>
      `;
      counter++;
   });
};


async function getOptimumMeetings() {
   let dayStarts = 1643580000;  // 8:00 31 января 2022 года (utc+10)
   let dayEnds = 1643616000;  // 18:00 31 января 2022 года (utc+10)

   let res = await fetch(`http://127.0.0.1:8000/api/getOptimumMeetings/${dayStarts}/${dayEnds}`);
   let optimumMeetings = await res.json();
   optimumMeetings = optimumMeetings.data

   document.querySelector('.optimum-meetings-list').innerHTML = '';
   counter = 0;
   optimumMeetings.forEach(() => {
      document.querySelector('.optimum-meetings-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <h5 class="card-title">${optimumMeetings[counter].name}</h5>
               <p class="card-text">Начало: ${optimumMeetings[counter].startstamp}</p>
               <p class="card-text">Конец: ${optimumMeetings[counter].endstamp}</p>
            </div>
         </div>
      `;
      counter++;
   });
};

async function addMeeting() {
   const name = document.getElementById('add-name').value,
   startStamp = document.getElementById('add-startStamp').value;
   endStamp = document.getElementById('add-endStamp').value

   let formData = new FormData();
   formData.append('name', name);
   formData.append('startstamp', startStamp);
   formData.append('endstamp', endStamp);

   const res = await fetch('http://127.0.0.1:8000/api/meetings', {
      method: 'POST',
      body: formData
   });

   await getMeetings();
   await getOptimumMeetings();
}

function selectMeeting(id, name, startStamp, endStamp) {
   global_id = id;
   document.getElementById('update-name').value = name;
   document.getElementById('update-startStamp').value = startStamp;
   document.getElementById('update-endStamp').value = endStamp;
}


async function updateMeeting() {
   const name = document.getElementById('update-name').value,
   startStamp = document.getElementById('update-startStamp').value;
   endStamp = document.getElementById('update-endStamp').value;

   let formData = new FormData();
   formData.append('_method', 'PUT');
   formData.append('name', name);
   formData.append('startstamp', startStamp);
   formData.append('endstamp', endStamp);

   const res = await fetch(`http://127.0.0.1:8000/api/meetings/${global_id}`, {
      method: 'POST',
      body: formData
   });

   await getMeetings();
   await getOptimumMeetings();
}

async function deleteMeeting(id) {
   let formData = new FormData();
   formData.append('_method', 'DELETE');

   const res = await fetch(`http://127.0.0.1:8000/api/meetings/${id}`, {
      method: "POST",
      body: formData
   });
   await getMeetings();
   await getOptimumMeetings();
}

getMeetings();
getOptimumMeetings();