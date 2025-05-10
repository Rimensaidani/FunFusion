<!-- fichier : tic-tac-toe.php -->
<div id="morpionModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
    background-color:#1a1a1a; color:white; border:2px solid #00ffcc; padding:20px; z-index:1000; border-radius:12px;">
  <h3>Morpion : Toi (❌) vs Ordi (⭕)</h3>
  <table id="morpion" style="margin: auto; border-collapse: collapse;">
    <tbody>
      <?php for ($i = 0; $i < 3; $i++): ?>
        <tr>
          <?php for ($j = 0; $j < 3; $j++): ?>
            <td onclick="jouer(this)" style="width:60px; height:60px; border:1px solid white; text-align:center; font-size:24px;"></td>
          <?php endfor; ?>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
  <p id="morpionStatus" style="margin-top:10px;"></p>
  <button onclick="fermerMorpion()">Fermer</button>
  <button onclick="resetMorpion()">Rejouer</button>
</div>

<style>
.btn-mini-jeu {
  background-color: #00ffcc;
  color: black;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s ease;
}
.btn-mini-jeu:hover {
  background-color: #00ccaa;
  color: white;
}
</style>

<script>
let joueur = "X";
let fini = false;

function ouvrirMorpion() {
  document.getElementById('morpionModal').style.display = 'block';
  resetMorpion();
}

function fermerMorpion() {
  document.getElementById('morpionModal').style.display = 'none';
}

function jouer(cell) {
  if (cell.textContent === "" && !fini && joueur === "X") {
    cell.textContent = "X";
    if (verifierGagnant("X")) {
      document.getElementById('morpionStatus').textContent = "Tu as gagné ! 🎉";
      fini = true;
      return;
    }
    if (estPlein()) {
      document.getElementById('morpionStatus').textContent = "Match nul 😐";
      fini = true;
      return;
    }
    joueur = "O";
    setTimeout(tourOrdinateur, 500); // petit délai pour rendre l'ordi plus réaliste
  }
}

function tourOrdinateur() {
  if (fini) return;
  const cells = Array.from(document.querySelectorAll("#morpion td"));
  const casesVides = cells.filter(c => c.textContent === "");

  if (casesVides.length > 0) {
    const choix = casesVides[Math.floor(Math.random() * casesVides.length)];
    choix.textContent = "O";

    if (verifierGagnant("O")) {
      document.getElementById('morpionStatus').textContent = "L'ordinateur a gagné ! 🤖";
      fini = true;
      return;
    }

    if (estPlein()) {
      document.getElementById('morpionStatus').textContent = "Match nul 😐";
      fini = true;
      return;
    }

    joueur = "X";
  }
}

function resetMorpion() {
  const cells = document.querySelectorAll("#morpion td");
  cells.forEach(c => c.textContent = "");
  joueur = "X";
  fini = false;
  document.getElementById('morpionStatus').textContent = "";
}

function estPlein() {
  const cells = document.querySelectorAll("#morpion td");
  return [...cells].every(c => c.textContent !== "");
}

function verifierGagnant(symbole) {
  const rows = document.querySelectorAll("#morpion tr");
  const grille = [...rows].map(r => [...r.children].map(td => td.textContent));

  for (let i = 0; i < 3; i++) {
    if (grille[i][0] === symbole && grille[i][1] === symbole && grille[i][2] === symbole) return true;
    if (grille[0][i] === symbole && grille[1][i] === symbole && grille[2][i] === symbole) return true;
  }

  if (grille[0][0] === symbole && grille[1][1] === symbole && grille[2][2] === symbole) return true;
  if (grille[0][2] === symbole && grille[1][1] === symbole && grille[2][0] === symbole) return true;

  return false;
}
</script>
