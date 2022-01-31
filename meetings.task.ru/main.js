let global_id = null;
async function getMeetings() {
   // эти данные нужно сделать функционально из даты начала и конца периода, за который 
   // нужна информация и преобразовать в универсальный формат unix, так как участники собрания могут находиться по всему миру и участвовать онлайн.

   let dayStarts = 1643580000;  // 8:00 31 января 2022 года (utc+10)
   let dayEnds = 1643616000;    // 18:00 31 января 2022 года (utc+10)

   let res = await fetch(`http://127.0.0.1:8000/api/getMeetings/${dayStarts}/${dayEnds}`);
   let meetings = await res.json();

   document.querySelector('.meetings-list').innerHTML = '';

   meetings.forEach((meeting) => {
      document.querySelector('.meetings-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <h5 class="card-title">${meeting.name}</h5>
               <p class="card-text">Начало: ${meeting.startstamp}</p>
               <p class="card-text">Конец: ${meeting.endstamp}</p>
               <a href="#" class="card-link" onclick="deleteMeeting(${meeting.id})">Удалить</a>
               <div><a href="#" class="card-link" onclick="selectMeeting('${meeting.id}', '${meeting.name}','${meeting.startstamp}', '${meeting.endstamp}')">Выбрать для редактирования</a></div>
            </div>
         </div>
      `
   });
};


async function getOptimumMeetings() {
   let dayStarts = 1643580000;  // 8:00 31 января 2022 года (utc+10)
   let dayEnds = 1643616000;  // 18:00 31 января 2022 года (utc+10)

   let res = await fetch(`http://127.0.0.1:8000/api/getOptimumMeetings/${dayStarts}/${dayEnds}`);
   let optimumMeetings = await res.json();

   document.querySelector('.optimum-meetings-list').innerHTML = '';

   optimumMeetings.forEach((meeting) => {
      document.querySelector('.optimum-meetings-list').innerHTML += `
         <div class="card" style="width: 18rem;">
            <div class="card-body">
               <h5 class="card-title">${meeting.name}</h5>
               <p class="card-text">Начало: ${meeting.startstamp}</p>
               <p class="card-text">Конец: ${meeting.endstamp}</p>
            </div>
         </div>
      `
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

   const res = await fetch(`http://127.0.0.1:8000/api/updateMeeting/${global_id}`, {
      method: 'POST',
      body: formData
   });

   await getMeetings();
   await getOptimumMeetings();
}

async function deleteMeeting(id) {
   const res = await fetch(`http://127.0.0.1:8000/api/deleteMeeting/${id}`, {
      method: "DELETE"
   });
      await getMeetings();
      await getOptimumMeetings();
}

getMeetings();
getOptimumMeetings();