<div class="main-ui" style="margin-bottom: 10px; margin-top: 10px; min-height: 200px;">
<!--
  <div class="progress">
    <div class="boxes">
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
      <div class="box"></div>
    </div>
    <div class="progress-inner"></div>
  </div>
-->
  <p class="status" style="font-size: 20px;">Il vous reste <span class="points-needed points">10</span> points de plus à avoir. Vous avez encore droit à <span class="mistakes-allowed points">2</span> erreurs.</p>

  <p class="problem" style="line-height: 100px;"></p>

  <form action="" class="our-form calcul-mental">
    <input type="text" class="our-field" autocomplete="off">
    <button>Valider</button>
  </form>


</div>

<div class="overlay">
  <div class="overlay-inner">
    <p class="end-message"></p>
    <button class="reset-button">Réessayer</button>
  </div>
</div>

<style>

 .calcul-mental input, .calcul-mental button{
	max-width: 268px;
	height: 35px;
	display: block;
	border-radius: 20px;
	border: none;
	outline: none;
	padding: 5px 20px;
	/* margin: auto; */
	margin-bottom: 12px;
	font-size: 20px;
	box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.25);
	margin-right: 5px;
}	
.points {
	font-weight: 900;
	font-size: 25px;
	color: red;
}

.main-ui {
  max-width: 800px;
  margin: 0 auto;
}

.our-form {
  display: flex;
  justify-content: center;
}

.status {
  text-align: center;
  font-size: .85rem;
}

.boxes {
  display: flex;
  width: 100%;
}

.progress {
  border: 1px solid #c7c7c7;
  border-right: none;
  position: relative;
}

.progress-inner {
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100%;
  background-color: #7ecc00;
  opacity: .57;
  transform: scaleX(0);
  transform-origin: center left;
  transition: transform .4s ease-out;
}

.box {
  height: 40px;
  border-right: 1px solid #c7c7c7;
  flex: 1;
}

.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(255, 255, 255, .82);
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  visibility: hidden;
  transition: all .33s ease-out;
  transform: scale(1.2);
  z-index: 1000000;
}

.overlay-inner {
  text-align: center;
  max-width: 700px;
}

body.overlay-is-open .overlay,
.overlay--visible {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}

body.overlay-is-open .main-ui,
.blurred {
  filter: blur(4px);
}

@keyframes showError {
  50% {
    color: red;
    transform: scale(1.2);
  }

  100% {
    color: #333;
    transform: scale(1);
  }
}

.animate-wrong {
  animation: .45s showError;
}

.problem {
  font-size: 5rem;
  margin: 0;
  text-align: center;
}

.end-message {
  font-size: 1.5rem;
  margin-top: 0;
}

.reset-button {
  font-size: 1.2rem;
  background-color: #004094;
  border: none;
  color: #FFF;
  border-radius: 7px;
  padding: 12px 20px;
  display: inline-block;
  outline: none;
  cursor: pointer;
}

.reset-button:hover {
  background-color: #00367e;
}

.reset-button:focus {
  background-color: #00275a;
}
</style>

<!-- partial -->
<script>
  const problemElement = document.querySelector(".problem")
  const ourForm = document.querySelector(".our-form")
  const ourField = document.querySelector(".our-field")
  const pointsNeeded = document.querySelector(".points-needed")
  const mistakesAllowed = document.querySelector(".mistakes-allowed")
  const progressBar = document.querySelector(".progress-inner")
  const endMessage = document.querySelector(".end-message")
  const resetButton = document.querySelector(".reset-button")
  
  let state = {
    score: 0,
    wrongAnswers: 0
  }
  
  function updateProblem() {
    state.currentProblem = generateProblem(100)
    problemElement.innerHTML = `${state.currentProblem.numberOne} ${state.currentProblem.operator} ${state.currentProblem.numberTwo}`
    ourField.value = ""
    ourField.focus()
  }
  
  updateProblem()
  
  function generateNumber(max) {
    return Math.floor(Math.random() * (max + 1))
  }
  
  function generateProblem(maximum) {
    return {
      numberOne: generateNumber(maximum),
      numberTwo: generateNumber(maximum),
      operator: ['+', '-', 'x'][generateNumber(2)]
    }
  }
  
  ourForm.addEventListener("submit", handleSubmit)
  
  function handleSubmit(e) {
    e.preventDefault()
  
    let correctAnswer
    const p = state.currentProblem
    if (p.operator == "+") correctAnswer = p.numberOne + p.numberTwo
    if (p.operator == "-") correctAnswer = p.numberOne - p.numberTwo
    if (p.operator == "x") correctAnswer = p.numberOne * p.numberTwo
  
    if (parseInt(ourField.value, 10) === correctAnswer) {
      state.score++
      pointsNeeded.textContent = 10 - state.score
      updateProblem()
      renderProgressBar()
    } else {
      state.wrongAnswers++
      mistakesAllowed.textContent = 2 - state.wrongAnswers
      problemElement.classList.add("animate-wrong")
      setTimeout(() => problemElement.classList.remove("animate-wrong"), 451)
    }
    checkLogic()
  }
  
  function checkLogic() {
    // if you won
    if (state.score === 10) {
      endMessage.textContent = "Félicitations ! Vous avez réussi :)"
      document.body.classList.add("overlay-is-open")
      setTimeout(() => resetButton.focus(), 331)
    }
  
    // if you lost
    if (state.wrongAnswers === 3) {
      endMessage.textContent = "Ooooh... Vous avez perdu :("
      document.body.classList.add("overlay-is-open")
      setTimeout(() => resetButton.focus(), 331)
    }
  }
  
  resetButton.addEventListener("click", resetGame)
  
  function resetGame() {
    document.body.classList.remove("overlay-is-open")
    updateProblem()
    state.score = 0
    state.wrongAnswers = 0
    pointsNeeded.textContent = 10
    mistakesAllowed.textContent = 2
    renderProgressBar()
  }
  
  function renderProgressBar() {
    progressBar.style.transform = `scaleX(${state.score / 10})`
  }
</script>
