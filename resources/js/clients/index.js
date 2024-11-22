$(document).ready(function() {
    console.log("jQuery is working!");
  });

axios.get('/api/test')
  .then(response => {
    console.log('Response:', response.data);
  })
  .catch(error => {
    console.error('There was an error!', error);
  });
