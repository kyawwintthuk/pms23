<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <div class="navbar-brand">
      <a href="index.php"><?php echo date('d-M-Y (l)') ?></a><br>
      <table>
        <tr>
          <td>Expiry</td>
          <td onclick="location.href='expiry.php';" style="cursor: pointer;">: [ 10 ]</td>
          <td style="padding-left: 10px; color: #a8bbea;">|</td>
          <td style="padding-left: 10px;">Low Stock</td>
          <td onclick="location.href='index.php';">: [ 100 ]</td>
        </tr>
        <tr>
          <td>Hike</td>
          <td onclick="location.href='index.php';">: [ 50 ]</td>
          <td style="padding-left: 10px; color: #a8bbea;">|</td>
          <td style="padding-left: 10px;">Drop</td>
          <td onclick="location.href='index.php';">: [ 0 ]</td>
        </tr>
      </table>

    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Expiry</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Low Stock</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Hike</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Drop</a>
        </li>
      </ul>
    </div>
  </div>
</nav>