document.addEventListener("DOMContentLoaded", () => {
  // meta counter
  let titleArea = document.querySelector(".meta-title input");
  let descriptionArea = document.querySelector(".meta-description textarea");
  let titleCounter = document.getElementById("char_count_title");
  let descriptionCounter = document.getElementById("char_count_description");

  if(titleCounter != null){
  countTitleCharacters();
  countDescriptionCharacters();

  function countTitleCharacters() {
    let counter = titleArea.value.length;
    titleCounter.textContent = counter;
    if (counter > 60) {
      titleCounter.classList.add('warning');
      titleArea.classList.add('warning');
    } else {
      titleCounter.classList.remove('warning');
      titleArea.classList.remove('warning');
    }
  };

  function countDescriptionCharacters() {
    let counter = descriptionArea.value.length;
    descriptionCounter.textContent = counter;
    if (counter > 160) {
      descriptionCounter.classList.add('warning');
      descriptionArea.classList.add('warning');
    } else {
      descriptionCounter.classList.remove('warning');
      descriptionArea.classList.remove('warning');
    }
  };

  titleArea.addEventListener("keyup", countTitleCharacters);
  descriptionArea.addEventListener("keyup", countDescriptionCharacters);
}

});
