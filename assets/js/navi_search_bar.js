//////////////////// SEARCH ICON IN NAVIGATION
let value = "";
const bodyNav = document.querySelector("body");
const searchBtn = document.getElementById("search-btn");
const searchForm = document.querySelector(".navi__search");
const searchInput = document.querySelector(".navi__search-input");
const user = document.getElementById("user_dropdown").value;
const searchResults = document.querySelector(".search__results");
const seeAllResults = document.querySelector(".search__see-all-results ");
searchResults.style.height = "0rem";

/////////////////////CREATE A formData
const formData2 = new FormData();

///////////////////// WHEN BODY IS CLICKED

bodyNav.addEventListener("click", function (e) {
  //console.log(e.target);
  if (e.target === searchInput || e.target === searchResults) {
    return;
  }
  searchResults.style.height = "0rem";
  seeAllResults.innerHTML = "";
  searchInput.value = "";
});

///////////////////// WHEN SEARCH ICON IS CLICKED

searchBtn.addEventListener("click", function () {
  searchForm.submit();
});

//////////////////// AJAX CALL TO ajax_search.php

searchInput.addEventListener("keyup", function () {
  searchResults.style.height = "21.2rem";
  let value = searchInput.value;
  formData2.append("value", value);
  formData2.append("user", user);
  ajaxUserSearch(formData2, value);
});

const ajaxUserSearch = async function (formData, value) {
  try {
    const response = await fetch("includes/handlers/ajax_search.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new error(
        `Something went wrong in fetching data", ${response.status}`
      );
    }

    let data = await response.text();
    //console.log(data);

    if (data.trim() == "") {
      searchResults.style.height = "0rem";
      seeAllResults.innerHTML = "";
    } else {
      searchResults.innerHTML = data;
      seeAllResults.innerHTML = `<a class='see-result' href='search.php?q=${value}'> See all Results </a>`;
      console.log(value);
    }
  } catch (err) {
    console.error(err.message);
  }
};
