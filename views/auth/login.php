<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 22rem;">
        <h3 class="text-center">Chatbot RRHH</h3>
        <form method="POST" action="/">
            <div class="mb-3">
                <label for="carnet" class="form-label">Carnet:</label>
                <input type="text" class="form-control" id="carnet" name="carnet">
                <div class="valid-feedback">
                    <?php echo $data['carnetError'];  ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="valid-feedback">
                    <?php echo $data['passwordError'];  ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        <p id="mensaje" class="mt-3 text-center text-danger"></p>
    </div>
</div>