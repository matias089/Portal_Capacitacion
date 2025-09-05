<?php
function calcularTotalPedido(array $productos, int $descuentoPorcentaje) {
    if ($descuentoPorcentaje < 0 || $descuentoPorcentaje > 100) {
        return false; 
    }

    $subtotal = 0;

    foreach ($productos as $producto) {
        if (!isset($producto['precio'], $producto['cantidad'])) {
            return false;
        }

        $subtotal += $producto['precio'] * $producto['cantidad'];
    }

    $descuento = $subtotal * ($descuentoPorcentaje / 100);
    $total = $subtotal - $descuento;

    return $total;
}

<?php
$productos = [
    ['precio' => 1000, 'cantidad' => 1],
    ['precio' => 250, 'cantidad' => 2],
    ['precio' => 150, 'cantidad' => 3]
];

$descuento = 10;

$total = calcularTotalPedido($productos, $descuento);

if ($total !== false) {
    echo "Total del pedido + descuento: $" . $total;
} else {
    echo "Error: el porcentaje de descuento es inválido o los datos están incorrectos.";
}
?>
