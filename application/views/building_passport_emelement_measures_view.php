
<div class="box left narrow">
	<div class="title">
		Личный кабинет
	</div>
	<div class="content">
		<!--Меню офиса-->
		<?php GetAsBlock("building_menu"); ?>
	</div>
</div>

<div class="box right wide">
	<div class="title">
		<div style="float: left">Счётчик № <?php echo $data['esnum']; ?></div>
		<div style="float: right"><?php echo $data['address']; ?>

		</div>
	</div>

	<div class="content height450">
		<!--Меню офиса-->
		<?php GetAsBlock("emelement_menu"); ?>	
		<!--/Меню офиса-->
		<div style="padding: 5px">
		<?php var_dump($data['measurements'])?>
		<table width="100%" class="simple">
		  <thead>
            <tr>
              <td rowspan="2">Дата</td>
              <td rowspan="2">Время</td>
              <td colspan="3">Количество электроэнергии </td>
              <td colspan="3">Разность показаний </td>
            </tr>
            <tr>
              <td>ТА</td>
              <td>Т1</td>
              <td>Т2</td>
              <td>ТА</td>
              <td>Т1</td>
              <td>Т2</td>
            </tr>
		</thead>
            <tr>
              <td>25.01.2013 </td>
              <td>12.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.02.2013 </td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
            <tr>
              <td>25.03.2013 </td>
              <td>15.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.04.2013 </td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
            <tr>
              <td>25.05.2013</td>
              <td>15.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.06.2013</td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
            <tr>
              <td>25.07.2013</td>
              <td>15.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.08.2013</td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
            <tr>
              <td>25.09.2013</td>
              <td>15.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.10.2013</td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
            <tr>
              <td>25.11.2013</td>
              <td>15.00</td>
              <td>29771</td>
              <td>13000</td>
              <td>16774</td>
              <td>5000</td>
              <td>2000</td>
              <td>3000</td>
            </tr>
            <tr>
              <td>25.12.2013</td>
              <td>15.00</td>
              <td>31056</td>
              <td>14788</td>
              <td>16999</td>
              <td>4600</td>
              <td>1300</td>
              <td>3300</td>
            </tr>
          </table>
		  </div>
		

		           
	</div>

		



