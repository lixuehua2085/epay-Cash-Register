SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `trade` (
  `id` int NOT NULL,
  `no` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `return_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `return_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `statu` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fail',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `trade`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `trade`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;