<?php
$joueurs = json_decode(file_get_contents("data/joueurs.json"), true);


function abrevPoste($poste)
{
  $map = [
    'GARDIEN' => 'G',
    'DEFENSEUR' => 'D',
    'DEFENSEUR CENTRAL' => 'DC',
    'LATERAL DROIT' => 'LD',
    'LATERAL GAUCHE' => 'LG',
    'MILIEU' => 'M',
    'MILIEU CENTRAL' => 'MC',
    'MILIEU DROIT' => 'MD',
    'MILIEU GAUCHE' => 'MG',
    'MILIEU/ATTAQUANT' => 'M/A',
    'AILIER DROIT' => 'AD',
    'AILIER GAUCHE' => 'AG',
    'ATTAQUANT' => 'A',
    'ATTAQUANT (11) / MILIEU (6)' => 'A/M',
    'MILIEU (6) ; (8)' => 'M',
    'LATERAL /DEFENSEUR CENTRAL' => 'LD/DC',
    'DEFENSEUR/LATERAL DROIT' => 'D/LD'
  ];
  $poste = strtoupper(trim($poste));
  return $map[$poste] ?? $poste;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Formation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Montserrat', Arial, sans-serif;
      background: #f4f6fa;
      margin: 0;
      padding: 0;
    }

    .container-formation {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
      gap: 40px;
      margin: 40px auto;
      max-width: 1200px;
    }

    .terrain {
      position: relative;
      width: 420px;
      height: 600px;
      background: url('assets/img/terrain.jpg') center/cover no-repeat, #0a3d62;
      border-radius: 24px;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .zone {
      position: absolute;
      width: 90px;
      height: 90px;
      background: rgba(255, 255, 255, 0.85);
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      color: #0a3d62;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: box-shadow 0.2s, background 0.2s;
      cursor: pointer;
      border: 2px solid #38ada9;
      font-size: 1.1em;
    }

    .zone:hover {
      box-shadow: 0 4px 16px #38ada9aa;
      background: #e0f7fa;
    }

    /* Placement des zones sur le terrain */
    #gardien {
      left: 165px;
      top: 500px;
    }

    #defenseur-g {
      left: 40px;
      top: 380px;
    }

    #defenseur-c {
      left: 165px;
      top: 370px;
    }

    #defenseur-d {
      left: 290px;
      top: 380px;
    }

    #milieu-g {
      left: 80px;
      top: 220px;
    }

    #milieu-c {
      left: 165px;
      top: 220px;
    }

    #milieu-d {
      left: 250px;
      top: 220px;
    }

    #attaquant {
      left: 165px;
      top: 80px;
    }

    /* Liste des joueurs (banc) */
    .banc-container {
      min-width: 250px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      padding: 24px 18px;
      margin-top: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .banc-container h3 {
      margin-top: 0;
      color: #0a3d62;
      font-size: 1.2em;
      margin-bottom: 16px;
    }

    #banc {
      list-style: none;
      padding: 0;
      margin: 0;
      width: 100%;
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 10px 16px;
    }

    #banc li {
      background: #38ada9;
      color: #fff;
      margin-bottom: 0;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 1em;
      font-weight: 500;
      cursor: grab;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 2px 6px rgba(56, 173, 169, 0.08);
      transition: background 0.2s, transform 0.2s;
    }

    #banc li:active {
      background: #0a3d62;
      transform: scale(1.03);
    }

    /* Responsive */
    @media (max-width: 900px) {
      .container-formation {
        flex-direction: column;
        align-items: center;
        gap: 24px;
      }

      .terrain {
        width: 98vw;
        max-width: 420px;
        height: 60vw;
        min-height: 400px;
      }

      .banc-container {
        width: 90vw;
        max-width: 350px;
      }
    }

    @media (max-width: 600px) {
      .terrain {
        width: 100vw;
        min-width: 280px;
        height: 70vw;
        min-height: 320px;
      }

      .zone {
        width: 60px;
        height: 60px;
        font-size: 0.9em;
      }
    }

    .remplacants-bar {
      width: 100%;
      max-width: 420px;
      margin: 24px auto 0 auto;
      display: flex;
      justify-content: center;
    }

    #remplacants {
      min-height: 60px;
      padding: 8px 12px;
      gap: 10px;
      overflow-x: auto;
      display: flex;
      flex-direction: row;
      align-items: center;
    }

    @media (max-width: 900px) {
      .remplacants-bar {
        max-width: 98vw;
      }

      #remplacants {
        max-width: 98vw;
      }
    }

    @media (max-width: 600px) {
      .remplacants-bar {
        max-width: 100vw;
      }

      #remplacants {
        max-width: 100vw;
        font-size: 0.9em;
      }
    }

    .container-formation {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: flex-start;
      gap: 40px;
      margin: 40px auto;
      max-width: 1200px;
    }

    .banc-container {
      min-width: 250px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      padding: 24px 18px;
      margin-top: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .remplacants-bar {
      width: 100%;
      max-width: 420px;
      margin: 24px auto 0 auto;
      display: flex;
      justify-content: center;
    }

    #remplacants {
      min-height: 60px;
      padding: 8px 12px;
      gap: 10px;
      overflow-x: auto;
      display: flex;
      flex-direction: row;
      align-items: center;
    }

    @media (max-width: 900px) {
      .container-formation {
        flex-direction: column;
        align-items: center;
        gap: 24px;
      }

      .banc-container {
        width: 90vw;
        max-width: 350px;
      }

      .remplacants-bar {
        max-width: 98vw;
      }

      #remplacants {
        max-width: 98vw;
      }
    }

    @media (max-width: 600px) {
      .remplacants-bar {
        max-width: 100vw;
      }

      #remplacants {
        max-width: 100vw;
        font-size: 0.9em;
      }
    }
  </style>
</head>

<body>
  <header>
    <h1>Disposition des joueurs</h1>
  </header>
  <div class="container-formation">
    <!-- Terrain de football -->
    <div>
      <div class="terrain">
        <div class="zone" id="attaquant" data-poste="Attaquant"><i class="fa-solid fa-futbol"></i><span>Attaquant</span>
        </div>
        <div class="zone" id="milieu-g" data-poste="Milieu G"><i class="fa-solid fa-person-running"></i><span>Milieu
            G</span></div>
        <div class="zone" id="milieu-c" data-poste="Milieu C"><i class="fa-solid fa-person-running"></i><span>Milieu
            C</span></div>
        <div class="zone" id="milieu-d" data-poste="Milieu D"><i class="fa-solid fa-person-running"></i><span>Milieu
            D</span></div>
        <div class="zone" id="defenseur-g" data-poste="Défenseur G"><i class="fa-solid fa-shield-halved"></i><span>Déf
            G</span></div>
        <div class="zone" id="defenseur-c" data-poste="Défenseur C"><i class="fa-solid fa-shield-halved"></i><span>Déf
            C</span></div>
        <div class="zone" id="defenseur-d" data-poste="Défenseur D"><i class="fa-solid fa-shield-halved"></i><span>Déf
            D</span></div>
        <div class="zone" id="gardien" data-poste="Gardien"><i class="fa-solid fa-handshake"></i><span>Gardien</span>
        </div>
      </div>
      <!-- Zone remplaçants SOUS le terrain -->
      <div class="remplacants-bar">
        <div id="remplacants" style="width: 100%; min-height: 60px; border-radius: 12px; background:rgba(56,173,169,0.12); border:2px dashed #38ada9; display:flex; flex-direction:row; align-items:center; justify-content:flex-start; font-size:1em; color:#38ada9; margin: 0 auto; position:relative;">
          <i class="fa-solid fa-users" style="margin-right:8px;"></i>
          <span style="margin-right:16px;">Remplaçants :</span>
          <!-- Les joueurs remplaçants seront ajoutés ici par drag & drop -->
        </div>
      </div>
    </div>
    <!-- Banc des joueurs à droite -->
    <div class="banc-container">
      <h3>Banc des joueurs <span style="color:#38ada9; font-weight:normal;">(<?php echo count($joueurs); ?>)</span></h3>
      <ul id="banc">
        <?php
        foreach ($joueurs as $joueur) {
          $nom = htmlspecialchars($joueur['nom']);
          $poste = !empty($joueur['poste']) ? abrevPoste($joueur['poste']) : '';
          $posteAff = $poste ? "<span style='color:#0a3d62;font-size:0.9em;font-weight:400;margin-left:8px;'>($poste)</span>" : "";

          // Couleur selon le poste abrégé
          $color = '';
          if ($poste === 'G') {
            $color = "background:#ff7043;color:#fff;"; // orange pour gardien
          } elseif ($poste === 'A' || strpos($poste, 'A') === 0) {
            $color = "background:#8e44ad;color:#fff;"; // violet pour attaquant
          }

          echo "<li draggable='true' data-nom=\"{$nom}\" class='joueur-item' style='{$color}'><i class='fa-solid fa-user'></i> {$nom} {$posteAff}</li>";
        }
        ?>
      </ul>
    </div>
  </div>
  <script>
    // Drag & drop vanilla JS
    let dragged = null;

    // Rendre chaque joueur draggable
    document.querySelectorAll('.joueur-item').forEach(item => {
      item.addEventListener('dragstart', function(e) {
        dragged = this;
        setTimeout(() => this.style.display = "none", 0);
      });
      item.addEventListener('dragend', function(e) {
        this.style.display = "";
        dragged = null;
      });
    });

    // Zones de drop (postes + remplaçants)
    document.querySelectorAll('.zone').forEach(zone => {
      zone.addEventListener('dragover', e => e.preventDefault());
      zone.addEventListener('drop', function(e) {
        e.preventDefault();
        if (!dragged) return;
        const nom = dragged.getAttribute('data-nom');
        // Empêcher de placer deux fois le même joueur
        if (document.querySelectorAll('.zone .joueur-item[data-nom="' + nom + '"]').length > 0) return;
        if (this.id === "remplacants") {
          // Limite à 3 remplaçants (modifiable)
          if (this.querySelectorAll('.joueur-item').length >= 5) return;
          // Empêcher doublon dans remplaçants
          if (this.querySelector('.joueur-item[data-nom="' + nom + '"]')) return;
          // Retirer du banc ou d'une zone
          if (dragged.parentElement.id === "banc" || dragged.parentElement.classList.contains('zone')) {
            dragged.parentElement.removeChild(dragged);
          }
          this.appendChild(dragged);
        } else {
          // Pour les postes sur le terrain
          // Si déjà un joueur sur ce poste, on ne fait rien
          if (this.querySelector('.joueur-item')) return;
          // Retirer du banc/remplaçants/zone précédente
          if (dragged.parentElement.id === "banc" || dragged.parentElement.id === "remplacants" || dragged.parentElement.classList.contains('zone')) {
            dragged.parentElement.removeChild(dragged);
          }
          this.appendChild(dragged);
        }
      });
    });

    // Permettre de remettre un joueur du terrain/remplaçants vers le banc
    document.getElementById('banc').addEventListener('dragover', e => e.preventDefault());
    document.getElementById('banc').addEventListener('drop', function(e) {
      e.preventDefault();
      if (!dragged) return;
      const nom = dragged.getAttribute('data-nom');
      // Empêcher doublons sur le banc
      if (document.querySelector('#banc .joueur-item[data-nom="' + nom + '"]')) return;
      // Retirer du terrain/remplaçants
      if (dragged.parentElement.classList.contains('zone') || dragged.parentElement.id === "remplacants") {
        dragged.parentElement.removeChild(dragged);
      }
      this.appendChild(dragged);
    });

    // Gestion du drop sur la zone des remplaçants
    const remplaZone = document.getElementById('remplacants');
    remplaZone.addEventListener('dragover', e => e.preventDefault());
    remplaZone.addEventListener('drop', function(e) {
      e.preventDefault();
      if (!dragged) return;
      const nom = dragged.getAttribute('data-nom');
      // Limite à 5 remplaçants (modifiable)
      if (this.querySelectorAll('.joueur-item').length >= 5) return;
      // Empêcher doublon dans remplaçants
      if (this.querySelector('.joueur-item[data-nom="' + nom + '"]')) return;
      // Retirer du banc ou d'une zone
      if (dragged.parentElement.id === "banc" || dragged.parentElement.classList.contains('zone')) {
        dragged.parentElement.removeChild(dragged);
      }
      this.appendChild(dragged);
    });
  </script>
  <script src="assets/js/script.js"></script>
</body>

</html>