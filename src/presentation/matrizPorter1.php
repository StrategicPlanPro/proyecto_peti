<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

include_once '../data/plan.php';

// Obtener el idusuario de la sesión
$idusuario = $_SESSION['idusuario'];

// Obtener la id del plan de la sesión
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Obtener el plan utilizando ambos IDs
$plan = $planData->obtenerPlanPorId($idPlan, $idusuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Externo Microentorno</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .btn-volver, .btn-siguiente {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover, .btn-siguiente:hover {
            background-color: #555;
        }

        .btn-siguiente {
            background-color: #333;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px; /* Espacio superior del contenedor */
            padding: 10px; /* Relleno interno del contenedor */
            
    </style>
</head>
<body>

    <div class="container2">
        <div class="form-content2">
            <h1 style="text-align: center;">Analisis Externo Microentorno: Matriz de Porter</h1>
            <div class="content">
                <p>
                El Modelo de las 5 Fuerzas de Porter estudia un determinado negocio en función de la amenaza de nuevos competidores y productos sustituivos, así como el poder de negociación de los proveedores y clientes, teniendo en cuenta el grado de competencia del sector. Esto proporciona una clara imagen de la situación competitiva de un mercado en concreto. El conjunto de las cinco fuerzas determina la intensidad competitiva, la rentabilidad del sector y, de forma derivada, las posibilidades futuras de éste. Por ejemplo, si un sector está obteniendo rendimientos escasos, es dudoso que disponga de recursos para financiar el desarrollo de productos sustitutivos dentro del mismo sector. 
                </p>

                <div class="image">
                    <img src="assets/images/modeloPorter.png" alt="Modelo Porter" class="image-external">
                </div>

                <p>
                Pasemos a repasar de forma abreviada como funciona cada una de las cinco fuerzas.
                </p>


                <h3>Amenaza de nuevos entrantesr</h3>
                <p>
                La aparición de nuevas empresas en el sector supone un incremento de recursos, de capacidad y, en principio, un intento de obtener una participación en el mercado a costa de otros que ya la tenían. La posibilidad de entrar en un sector depende fundamentalmente de dos factores: la capacidad de reacción de las empresas que ya están (tecnológica, financiera, productiva, etc.) y las denominadas barreras de entrada (obstáculos para el ingreso). Entre las barreras de entrada, las más características son:
 Economía de escala. Reducción de costes unitarios debido al volumen (vinculada a menudo a reducciones por efecto experiencia), como por ejemplo, coches, aviones…
 Grado de diferenciación del producto/servicio. La fidelidad de los clientes obliga a realizar inversiones muy grandes (y arriesgadas) para desalojar al suministrador tradicional. Crítico en los mercados en los que la confianza es fundamental (bancos, farmacéuticas, etc.)
 Necesidades de capital. Las necesidades de capital, especialmente cuando éste tiene que ser desembolsado inicialmente o su recuperación, en caso de fallo, es difícil, constituye una barrera muy importante (coches, acero, etc.)
 Costes de cambio. Existen multitud de productos y servicios en los que el comprador tiene que asumir un coste extra si quiere cambiar de proveedor, principalmente por aspectos logísticos (entrenamiento, repuestos, almacenes, etc.)                                                                                                             Acceso a los canales de distribución. El control de los canales de distribución puede dificultar seriamente el acceso a un mercado. El canal puede cargar sobreprecios y los competidores bajar los suyos.
Otros factores. Dentro de este apartado podemos incluir las patentes, el acceso privilegiado a materias primas, la ubicación, las ayudas gubernamentales, etc.
                </p>
                

                <h3>Rivalidad de los competidores</h3>
                <p>
                La rivalidad aparece cuando uno o varios competidores sienten la presión o ven la oportunidad de mejorar. El grado de rivalidad depende de una serie de factores estructurales, entre los que podemos destacar:
 Gran número de competidores, o competidores muy equilibrados.
 Crecimiento lento en el mercado. Cuando los mercados se estancan, la única forma de mejorar los resultados propios es arrebatar cuota a la competencia
 Costes fijos o de almacenamiento elevados. Al darse esa situación, es necesario hacer un gran esfuerzo para operar a plena capacidad, o al menos por encima del punto muerto.
 Baja diferenciación de productos. El consumidor se ve atraído por el precio, y los competidores tenderán a bajarlo.
 Intereses estratégicos. En determinados mercados, puede ocurrir que varias empresas importantes intenten, de forma simultánea, establecer una posición sólida y utilicen para ello recursos desproporcionados.
 Barreras de salida. Cuando los competidores tienen dificultades para salir de un mercado que ha perdido interés, mantendrán una intensidad competitiva alta, si las barreras de salida son importantes. Entre las barreras de salida podemos destacar los activos especializados, los costes fijos de salida, las restricciones sociales o las barreras emocionales.
                </p>

                <h3>Presión de los productos sustitutivos</h3>
                <p>
                El nivel de precio/calidad de los productos sustitutivos limita el nivel de precios de la industria. Los productos sustitutivos pueden ser fabricados por empresas pertenecientes o ajenas al sector (situación peligrosa). Las empresas del sector pueden reaccionar en bloque, no hacerlo en absoluto, o cambiar de necesidad satisfecha adaptando el producto (un crucero no puede competir con el avión en el transporte de viajeros, pero es un medio de vacaciones de lujo inigualable). Desde la óptica estratégica, hay que prestar mucha atención a los “sustitutivos no evidentes” (ejemplo, videoconferencia contra hotel más avión).
                </p>
                <p>
                    <strong>Ejemplos:</strong> Buena implantación en el territorio, notoriedad de la marca, capacidad de innovación, recursos financieros adecuados, ventajas en costes, líder en el mercado, buena imagen entre los consumidores, etc.
                </p>

                <h3>Poder de negociación de los compradores/clientes</h3>
                <p>
                Los compradores fuerzan los precios a la baja y la calidad al alza, en perjuicio del beneficio de la industria. Su poder aumenta si:
Están concentrados, o compran grandes volúmenes relativos
El coste de la materia prima es importante
Los productos no son diferenciados
El coste de cambiar de proveedor es pequeño
No hay amenaza de integración
Tienen información total
La calidad no es importante
                </p>
                

                <h3>Poder de negociación de los proveedores</h3>
                <p>
                 Los proveedores poderosos pueden amenazar con subir los precios y/o disminuir la calidad. Las empresas del sector pueden ver disminuidos sus beneficios si no consiguen repercutir los incrementos al consumidor final. Su poder aumenta si:
Está más concentrado que el sector que compra
No están obligados a competir con sustitutivos
El comprador no es cliente importante
El producto es importante para el comprador
El producto está diferenciado
Representan una amenaza de integración
                </p>

                <p>
                    <strong>Según Porter, estas fuerzas se encuentran en interacción y cambio permanente. Nuestro objetivo será situar a nuestra empresa en una posición en la que se pueda defender de las amenazas que las fuerzas competitivas plantean.
                    </strong> 
                </p>

                
            </div>

            <!-- Contenedor de los botones -->
            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="matrizPorter2.php" class="btn-siguiente">Siguiente</a>
            </div>

        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
        
    </div>

</body>
</html>
