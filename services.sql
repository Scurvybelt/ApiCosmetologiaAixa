/*
 Created by            : DanCruise
 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50733

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 02/11/2022 11:00:55
*/

CREATE DATABASE cosmetologia;
use cosmetologia;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `description` varchar(510) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` decimal(20, 2) NULL DEFAULT NULL,
  `img` varchar(255),
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO services (name, description, price, img, category) VALUES
("Glúteos", 'Ayuda a tonificar y reafirmar la zona de los glúteos mediante el uso de aparatología medica y activos específicos.', 450, '', "Corporales"),
("Reductivo", 'Terapia que ayuda a movilizar el tejido adiposos en personas con sobrepeso, y grasas localizadas reduciendo medidas', 4000, '', "Corporales"),
("Anticelulitico", 'Procedimiento efectivo para eliminar la grasa localizada favoreciendo la ruptura de los adipocitos (células grasas).', 4000, '', "Corporales"),
("Reafirmante", 'Tratamiento no invasivo que combina tratamientos con aparatología médica y tratamientos manuales para acceder de manera profunda, cómoda y segura a las diferentes capas del tejido.', 4000, '', "Corporales"),
("Limpieza Profunda de Espalda", 'Ayuda a la eliminación de las impurezas, barritos y espinillas, utilizando productos con principios activos saponificantes y secantes, finalizando con una mascarilla secante y crema hidratante acompañada de un masaje relajante.', 450, '', "Corporales"),
("Exfoliación", 'Ayuda a renovar la epidermis y conservarla en buen estado y resplandeciente.', 700, '', "Corporales"),
("Baño de Novia", 'Es el método ideal para eliminar las células muertas, rejuveneciendo la piel, dejándola suave y radiante.', 950, '', "Corporales"),
("Radiofrecuencia", 'Ayuda en la formación de nuevo colágeno, favorece el drenaje linfático, la circulación de la piel y el tejido subcutáneo.', 600, '', "Corporales"),
("Drenaje Linfático", 'Masaje, suave y ligero, que se aplica sobre el sistema circulatorio y cuyo objetivo es movilizar los líquidos del organismo para favorecer la eliminación de las sustancias de desecho que se acumulan en el líquido que ocupa el espacio entre las célula', 400, '', "Corporales"),
("Mesoterapia Virtual", 'Tratamiento sin agujas y sin dolor para tratar celulitis, flacidez, envejecimiento cutáneo o estrías.', 500, '', "Corporales"),
("Limpieza Facial Profunda", 'Mejora el aspecto de la piel, corrige la dilatación de los poros, elimina las células más superficiales y extrae los famosos "puntos o negros".', 350, '', "Faciales"),
("Limpieza Facial + Microdermo", 'Elimina las capas mas superficiales de la piel, ideal para mantener la piel joven, suave y limpia.', 500, '', "Faciales"),
("Radiofrecuencia Facial", 'Combate de manera efectiva la flacidez y regenera el colágeno para lucir una piel firme y tersa.', 550, '', "Faciales"),
("Ultrasonido", 'Ayuda a la prevención de arrugas mediante la regeneración de la elastina, luciendo una piel más hidratada.', 500, '', "Faciales"),
("Mascara Led", 'Rejuvenece las células de la piel, aumentando la hidratación de manera inmediata, reduciendo enrojecimiento e inflamación para lucir un rostro más joven y saludable.', 350, '', "Faciales"),
("Hidratación", 'Consigue una hidratación extra y el equilibrio de la piel obteniendo de forma inmediata un cutis sedoso y luminoso.', 350, '', "Faciales"),
("Desensibilizante", 'Ayuda a reforzar las defensas de la piel, consiguiendo disminuir progresivamente la sensibilidad y mejorando duraderamente su umbral de tolerancia', 400, '', "Faciales"),
("Despigmentante", 'Ideal para aclarar la piel y eliminar las manchas provocadas por el sol.', 500, '', "Faciales"),
("Contorno de Ojos", 'Ayuda a reducir ojeras y líneas de expresión.', 550, '', "Faciales"),
("Control de Acné", 'Ayuda a reducir el acné a través de equipos y productos a nivel cosmecéutico con acción bactericida, oxigenante y cicatrizante.', 500, '', "Faciales"),
("Reafirmación Facial", 'Ayuda a reducir las arrugas y los efectos del envejecimiento.', 550, '', "Faciales"),
("Microdermoabrasión", 'Tratamiento estético que mejora el aspecto de nuestra piel. Es muy similar al peeling químico, quita la piel superficial donde se encuentran las imperfecciones.', 500, '', "Faciales"),
("Luz Pulsada", 'Tratamiento idoneo para tratar diferentes afecciones de la piel tales como acné, arrugas, pigmentaciones, cicatrices y fragilidad capilar.', 750, '', "Faciales"),
("Cara Completa", "Depilación láser IPL/SHR para rostro completo", 350, "", "Depilación"),
("Bigote", "Depilación láser IPL/SHR para bigote", 150, "", "Depilación"),
("Barba Caballero", "Depilación láser IPL/SHR para barba", 250, "", "Depilación"),
("Patillas", "Depilación láser IPL/SHR para patillas", 150, "", "Depilación"),
("Axilas", "Depilación láser IPL/SHR para axilas", 250, "", "Depilación"),
("Pecho Caballero", "Depilación láser IPL/SHR para pecho masculino", 300, "", "Depilación"),
("Espalda", "Depilación láser IPL/SHR para espalda", 450, "", "Depilación"),
("Brazos Completos", "Depilación láser IPL/SHR para brazos completos", 400, "", "Depilación"),
("Bikini Básico", "Depilación láser IPL/SHR para zona bikini básica", 250, "", "Depilación"),
("Bikini Brasileño", "Depilación láser IPL/SHR para bikini brasileño", 350, "", "Depilación"),
("Abdomen", "Depilación láser IPL/SHR para abdomen", 300, "", "Depilación"),
("Glúteos", "Depilación láser IPL/SHR para glúteos", 300, "", "Depilación"),
("Coxis (lumbares)", "Depilación láser IPL/SHR para zona lumbar", 200, "", "Depilación"),
("Piernas Completas", "Depilación láser IPL/SHR para piernas completas", 600, "", "Depilación"),
("1/2 Piernas", "Depilación láser IPL/SHR para media pierna", 400, "", "Depilación"),
("Relajante", "Ayuda relajar el cuerpo y la mente, promoviendo la respiración profunda y eliminando el sentimiento de ansiedad.", 500, "", "Masajes"),
("Descontracturante", "Ayuda a relajar la musculatura y disolver las contracturas que se producen por estrés, exceso de ejercicio, malas posturas, falta de descanso o una vida demasiado sedentaria.", 650, "", "Masajes"),
("Reflexo-espalda", "Ayuda a relajar los musculos de la espalda, dando sensación de alivio a través de un masaje específico con presiones suaves.", 350, "", "Masajes"),
("Piernas Cansadas", "Ayuda a disminuir las molestias provocadas por las varices y reactivando la circulación sanguinea.", 350, "", "Masajes"),
("Post-Operatorio", "Tratamiento que se recomienda después de haberte sometido a una cirugía ya sea estética o no.", 500, "", "Masajes"),
("Day Spa", "Momento de relajación integral que incluye exfoliación corporal, envoltura hidratante, masaje relajante y tratamiento facial.", 1500, "", "Masajes");