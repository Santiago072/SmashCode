<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glosario Médico — SmashCode</title>
  <link rel="stylesheet" href="<?= PROYECTO_PATH ?>/assets/css/estilos.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= PROYECTO_PATH ?>/assets/css/layout.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= PROYECTO_PATH ?>/assets/css/aprendiz.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script>
    (function(){var t=localStorage.getItem('smashcode_tema');if(t)document.documentElement.setAttribute('data-theme',t);})();
  </script>
</head>
<body>
<div class="contenedor-app">
  <?php include dirname(__DIR__) . '/layouts/aprendiz_sidebar.php'; ?>

  <main class="contenido-principal">
    <div class="glosario-container">
      
      <!-- HEADER -->
      <div class="header-seccion">
        <i class="fas fa-book-medical"></i>
        <div>
          <h1>Glosario Clínico Bilingüe</h1>
          <p>Consulta términos técnicos, categorías gramaticales, áreas de enfermería y escucha pronunciaciones IPA inmediatas.</p>
        </div>
      </div>

      <!-- FORMULARIO DE FILTROS -->
      <form class="filtros-card" method="GET" action="<?= PROYECTO_PATH ?>/aprendiz/glosario">
        <div class="filtros-row">
          
          <!-- Búsqueda texto -->
          <div class="filtro-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" name="q" class="filtro-campo" placeholder="Buscar término..." value="<?= htmlspecialchars($busqueda) ?>">
          </div>

          <!-- Filtro Área -->
          <div class="filtro-input-wrap">
            <i class="fas fa-hospital"></i>
            <select name="area" class="filtro-campo" style="padding-left: 38px;">
              <option value="">Todas las Áreas</option>
              <?php foreach ($areas as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $areaId === $a['id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Filtro Categoría -->
          <div class="filtro-input-wrap">
            <i class="fas fa-tags"></i>
            <select name="categoria" class="filtro-campo" style="padding-left: 38px;">
              <option value="">Todas las Categorías</option>
              <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $categoriaId === $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Filtro Nivel -->
          <div class="filtro-input-wrap">
            <i class="fas fa-graduation-cap"></i>
            <select name="nivel" class="filtro-campo" style="padding-left: 38px;">
              <option value="">Todos los Niveles</option>
              <?php foreach ($niveles as $n): ?>
                <option value="<?= $n['id'] ?>" <?= $nivelId === $n['id'] ? 'selected' : '' ?>><?= htmlspecialchars($n['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div style="display:flex; justify-content:space-between; align-items:center;">
          <small style="color:var(--gris-medio); font-weight:700;">Resultados encontrados: <?= count($vocabulario) ?></small>
          <div style="display:flex; gap:12px;">
            <?php if ($busqueda || $areaId || $categoriaId || $nivelId): ?>
              <a href="<?= PROYECTO_PATH ?>/aprendiz/glosario" class="btn-gris" style="text-decoration:none; padding:10px 20px; display:inline-flex; align-items:center; border-radius:10px; font-weight:800; font-size:0.85rem;">Limpiar</a>
            <?php endif; ?>
            <button type="submit" class="btn-filtrar">
              <i class="fas fa-filter"></i> Filtrar
            </button>
          </div>
        </div>
      </form>

      <!-- TABLA DE RESULTADOS -->
      <div class="vocab-table-card">
        <?php if (empty($vocabulario)): ?>
          <div style="padding: 40px; text-align:center; color:var(--texto-tenue);">
            <i class="fas fa-face-frown" style="font-size: 3rem; color:var(--gris-claro); margin-bottom:12px; display:block;"></i>
            <h3>No se encontraron términos clínicos</h3>
            <p style="font-size: 0.85rem; margin-top:4px;">Intenta ajustando los criterios de búsqueda o filtros.</p>
          </div>
        <?php else: ?>
          <table class="vocab-table">
            <thead>
              <tr>
                <th>Término en Inglés</th>
                <th>Traducción</th>
                <th>Metadata</th>
                <th>Ejemplo Contextualizado</th>
                <th style="width: 60px; text-align:center;">Audio</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vocabulario as $v): ?>
                <tr class="vocab-row">
                  <td>
                    <div class="term-english">
                      <?= htmlspecialchars($v['termino_en']) ?>
                      <?php if ($v['transcripcion_ipa']): ?>
                        <span class="term-ipa" title="Transcripción Fonética IPA"><?= htmlspecialchars($v['transcripcion_ipa']) ?></span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <span class="term-spanish"><?= htmlspecialchars($v['termino_es']) ?></span>
                  </td>
                  <td>
                    <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
                      <?php if ($v['area_nombre']): ?>
                        <span class="tag-badge area" title="Área Clínica"><i class="fas fa-hospital-alt" style="margin-right:4px;"></i><?= htmlspecialchars($v['area_nombre']) ?></span>
                      <?php endif; ?>
                      <?php if ($v['categoria_nombre']): ?>
                        <span class="tag-badge cat" title="Categoría Gramatical"><i class="fas fa-tag" style="margin-right:4px;"></i><?= htmlspecialchars($v['categoria_nombre']) ?></span>
                      <?php endif; ?>
                      <?php if ($v['nivel_nombre']): ?>
                        <span class="tag-badge nivel" title="Nivel Académico"><i class="fas fa-layer-group" style="margin-right:4px;"></i><?= htmlspecialchars($v['nivel_nombre']) ?></span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="term-example" title="Contexto de uso clínico">
                      <?= htmlspecialchars($v['oracion_ejemplo']) ?>
                    </div>
                  </td>
                  <td style="text-align:center;">
                    <button class="btn-audio-play" onclick="speakWord('<?= addslashes($v['termino_en']) ?>')" title="Escuchar término en inglés">
                      <i class="fas fa-volume-up"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

    </div>
  </main>
</div>

<script>
  function speakWord(text) {
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
      let utterance = new SpeechSynthesisUtterance(text);
      utterance.lang = 'en-US';
      utterance.rate = 0.9;
      
      let voices = window.speechSynthesis.getVoices();
      let enVoice = voices.find(v => v.lang.startsWith('en'));
      if (enVoice) utterance.voice = enVoice;
      
      window.speechSynthesis.speak(utterance);
    } else {
      console.log("Audio Speech synthesis not supported.");
    }
  }

  // Pre-cargar voces al inicio
  if ('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = () => {};
  }
</script>
<script src="<?= PROYECTO_PATH ?>/assets/js/tema.js"></script>
</body>
</html>
