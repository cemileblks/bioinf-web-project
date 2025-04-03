document.addEventListener('DOMContentLoaded', function () {
  const logoutLink = document.querySelector('.logout-link');
  if (logoutLink) {
    logoutLink.addEventListener('click', function (e) {
      e.preventDefault();
      const confirmed = confirm("Are you sure you want to log out?");
      if (confirmed) {
        window.location.href = "https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/logout_user.php";
      }
    });
  }
});
