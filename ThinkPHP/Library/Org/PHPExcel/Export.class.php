<?php

namespace Org\PHPExcel;
import('Org.PHPExcel.PHPExcel');
import('Org.PHPExcel.PHPExcel.IOFactory');

class Export {

    static $COLUMNS = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
    private $row = 1;
    private $column_index = 0;
    private $objPHPExcel;
    private $sheet_index = 0;

    public function __construct() {
        $this->objPHPExcel = new \PHPExcel();
    }
    
    /**
     * 导出Excel
     * @param type $name 文件名
     * @param type $titles  列名
     * @param type $data 数据
     */
    public function export($name, $titles, $data, $tableName = '',$width_array = null) {
        $tableName !== '' && $this->setTableName($tableName,count($titles) - 1);
        $width_array !== null && $this->setAllWidth($width_array);
        $this->setData($titles, $data);
        $this->output($name);
    }

    public function output($name){
        $fileName = $name . time(); //文件名称
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $fileName . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * 设置第一行名称
     * @param type $tableName 名称
     * @param type $col_num 合并单元格列数
     */
    public function setTableName($tableName, $col_num){
        $this->objPHPExcel->setActiveSheetIndex($this->sheet_index)->setCellValue('A1', $tableName);
        is_numeric($col_num) && $col_num > 0 && $this->objPHPExcel->getActiveSheet()->mergeCells('A1:' . self::$COLUMNS[$col_num] . $this->row); //合并单元格
        $this->objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(array('font'=>array('bold' => true,'size'=>14)))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->objPHPExcel->getActiveSheet()->setTitle($tableName);
        $this->row ++;
    }
    
    /**
     * 填充数据
     * @param type $titles 列名
     * @param type $data 数据
     */
    public function setData($titles, $data){
        //設置列名
        $this->column_index = 0;
        foreach ($titles as $key => $value) {
            $this->objPHPExcel->setActiveSheetIndex($this->sheet_index)->setCellValue(self::$COLUMNS[$this->column_index++] . $this->row, $value);
        }
        //將數據賦值給單元格
        foreach ($data as $row) {
            $this->row ++;
            $this->column_index = 0;
            foreach ($titles as $key => $value) {
                $this->objPHPExcel->getActiveSheet()->setCellValue(self::$COLUMNS[$this->column_index++] . $this->row, $row[$key]);
            }
        }
    }
    
    /**
     * 设置sheet索引，操作第$num个sheet
     * @param type $num
     */
    public function setSheetIndex($num=0){
        $num > $this->objPHPExcel->getSheetCount() -1 && $this->objPHPExcel->createSheet($num);
        $this->objPHPExcel->setActiveSheetIndex($num);
        $this->sheet_index = $num;
    }
    
    /**
     * 设置行数
     * @param type $num
     */
    public function setRow($num=1){
        is_numeric($num) && $num>0 && $this->row = $num;
    }

    /**
     * 将字符串转成UTF8编码
     * @param type $str
     * @return string
     */
    public function convertUTF8($str){
       if(empty($str)) return '';
       return  iconv('gb2312', 'utf-8', $str);
    }
    
    /**
     * 单元格设置超链接
     * @param type $cell
     * @param type $url
     */
    public function setLink($cell,$url){
        $this->objPHPExcel->setActiveSheetIndex($this->sheet_index)->getCell($cell)->getHyperlink()->setUrl($url);
    }

    /**
     * 设置单元格宽度
     * @param type $width
     */
    public function setWidth($col,$width){
        $this->objPHPExcel->setActiveSheetIndex($this->sheet_index)->getColumnDimension($col)->setWidth($width);
    }
    
    /**
     * 设置多个单元格宽度
     * @param type $width_array
     */
    public function setAllWidth($width_array){
        if(is_array($width_array)){
            for($i=0;$i < count($width_array);$i++){
                is_numeric($width_array[$i]) && $this->setWidth(self::$COLUMNS[$i], $width_array[$i]);
            }
        }
    }
}
