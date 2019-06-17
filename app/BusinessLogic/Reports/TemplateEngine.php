<?php
namespace App\BusinessLogic\Reports;

use Carbon\Carbon;

class TemplateEngine extends XtractReport
{
    private $Template;
    private $ReportData;
    private $pdf;

    public function __construct($Template, $ReportData)
    {
        parent::__construct($Template);
        $this->ReportData = $ReportData;
        $this->Template = $Template;
        // $this = new XtractReport($ReportData);
    }

    public function generate()
    {
        // set meta data
        // $this->setMetaData();

        $this->AddPage();
        $this->templateParser($this->Template->body);

        // close report by updating total number of pages in footer, cleanup etc.
        $this->finish();
        // make a name for the report
        $Filename = $this->bookmarkEngine($this->Template->file_info->filename, $this->ReportData)[0];
        $Filename = str_replace('.pdf', '', $Filename);
        $Filename = str_replace(['/','\\',' '], '_', $Filename);

        return $this->Output($Filename.'.pdf', 'S');
    }

    public function Header()
    {
        if (isset($this->Template->header)) {
            $this->templateParser($this->Template->header);
        }
    }

    public function Footer()
    {
        if (isset($this->Template->footer)) {
            $this->templateParser($this->Template->footer);
        }
    }

    private function templateParser($SubTemplate, $IteratableBookmark = null, $DataSet = null)
    {
        if (is_null($DataSet)) {
            $DataSet = [$this->ReportData];
        }
        if (!is_null($IteratableBookmark)) {
            $DataSet = $this->getReportData($IteratableBookmark, $DataSet, true);
        }
        // we need to know if the dataset is an object or an array of objects
        // but even "objects" are returned as arrays here. The trick below is
        // based on objects being associative arrays (indexes are names) vs
        // arrays of objects being non-associative array (is this the name?)
        // the indicies are numbers.
        if (isset($DataSet[0])) {
            foreach ($DataSet as $Data) {
                foreach ($SubTemplate as $Instruction) {
                    $this->handleInstruction($Instruction, $Data);
                }
            }
        } else {
            foreach ($SubTemplate as $Instruction) {
                $this->handleInstruction($Instruction, $DataSet);
            }
        }
    }

    private function handleInstruction($Instruction, $Data)
    {
        if (strpos($Instruction, '(') !== false) {
            $Function = substr($Instruction, 0, strpos($Instruction, '('));
            $Tail = substr($Instruction, strpos($Instruction, ')'));
            $InitialArgs = str_replace([$Function.'(', $Tail], '', $Instruction);
        } else {
            \Log::info('=====No paren======');
            \Log::info($Instruction);
        }
        $Args = $this->bookmarkEngine($InitialArgs, $Data);

        switch ($Function) {
            case 'AcceptPageBreak':
                $this->AcceptPageBreak();
                break;
            case 'AddLink':
                $this->AddLink();
                break;
            case 'AddPage':
                $this->AddPage($Args[0], $Args[1], $Args[2]);
                break;
            case 'Cell':
                $this->Cell($Args[0], $Args[1], $Args[2], $Args[3], $Args[4], $Args[5], $Args[6], $Args[7]);
                break;
            case 'Image':
                $this->Image($Args[0], $Args[1], $Args[2], $Args[3], $Args[4], $Args[5], $Args[6], $Args[7]);
                break;
            case 'Line':
                $this->Line($Args[0], $Args[1], $Args[2], $Args[3]);
                break;
            case 'Link':
                $this->Link($Args[0], $Args[1], $Args[2], $Args[3], $Args[4]);
                break;
            case 'Ln':
                if ($Args[0] === '') {
                    $this->Ln();
                } else {
                    $this->Ln(strval($Args[0]));
                }
                break;
            case 'MultiCell':
                $Y = $this->getY();
                $X = $this->getX();
                $this->MultiCell($Args[0], $Args[1], $Args[2], $Args[3], $Args[4], $Args[5]);
                //multicells stack as columns for some reason but it makes more sense
                //to have them be on the same line like regular cells
                $this->SetXY($X + $Args[0], $Y);
                break;
            case 'Circle':
                $this->Circle($Args[0], $Args[1], $Args[2], $Args[3]);
                break;
            case 'Elipse':
                $this->Circle($Args[0], $Args[1], $Args[2], $Args[3], $Args[4]);
                break;
            case 'PageNo':
                $this->PageNo();
                break;
            case 'Rect':
                $this->Rect($Args[0], $Args[1], $Args[2], $Args[3], $Args[4]);
                break;
            case 'SetAuthor':
                $this->SetAuthor($Args[0], $Args[1]);
                break;
            case 'SetCreator':
                $this->SetCreator($Args[0], $Args[1]);
                break;
            case 'SetDrawColor':
                if (!isset($Args1)) {
                    // try to parse a color by name
                    $Color = $this->parseColorByName($Args[0]);
                    $Args[0] = $Color[0];
                    $Args[1] = $Color[1];
                    $Args[2] = $Color[2];
                }
                $this->SetDrawColor($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetFillColor':
                if (!isset($Args1)) {
                    // try to parse a color by name
                    $Color = $this->parseColorByName($Args[0]);
                    $Args[0] = $Color[0];
                    $Args[1] = $Color[1];
                    $Args[2] = $Color[2];
                }
                $this->SetFillColor($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetFont':
                $this->SetFont($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetFontSize':
                $this->SetFontSize($Args[0]);
                break;
            case 'SetKeywords':
                $this->SetKeywords($Args[0], $Args[1]);
                break;
            case 'SetLeftMargin':
                $this->SetLeftMargin($Args[0]);
                break;
            case 'SetLineWidth':
                $this->SetLineWidth($Args[0]);
                break;
            case 'SetLink':
                $this->SetLink($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetMargins':
                $this->SetMargins($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetRightMargin':
                $this->SetRightMargin($Args[0]);
                break;
            case 'SetSubject':
                $this->SetSubject($Args[0], $Args[1]);
                break;
            case 'SetTextColor':
                if (!isset($Args1)) {
                    // try to parse a color by name
                    $Color = $this->parseColorByName($Args[0]);
                    $Args[0] = $Color[0];
                    $Args[1] = $Color[1];
                    $Args[2] = $Color[2];
                }
                $this->SetTextColor($Args[0], $Args[1], $Args[2]);
                break;
            case 'SetTitle':
                $this->SetTitle($Args[0], $Args[1]);
                break;
            case 'SetTopMargin':
                $this->SetTopMargin($Args[0]);
                break;
            case 'SetX':
                $this->SetX(strval($Args[0]));
                break;
            case 'SetXY':
                $this->SetXY(strval($Args[0]), strval($Args[1]));
                break;
            case 'SetY':
                $this->SetY(strval($Args[0]));
                break;
            case 'Text':
                $this->Text($Args[0], $Args[1], $Args[2]);
                break;
            case 'Write':
                $this->Write($Args[0], $Args[1], $Args[2]);
                break;
            case 'FontAwesome':
                $this->fontAwesomeIcon($Args[0], $Args[1], $Args[2]);
                break;
            case 'Template':
                //template needs the bookmark as the second arg but if we use
                //Args[1] here it is the "resolved" bookmark.
                if (Count(explode(',', $InitialArgs)) >= 2) {
                    $bookmark = explode(',', $InitialArgs)[1];
                } else {
                    $bookmark = null;
                }
                $this->templateParser($this->Template->{$Args[0]}, $bookmark, $Data);
                break;
            case 'Systemics':
                $Data = $this->getSystemics($this->ReportData['Injections']);
                $this->templateParser($this->Template->{$Args[0]}, null, $Data);
                break;
            case 'Locals':
                $Data = $this->getLocals($this->ReportData['Injections']);
                $this->templateParser($this->Template->{$Args[0]}, null, $Data);
                break;
            case 'Tmp1':
                $this->tmp1 = $Args[0];
                break;
            case 'Tmp2':
                $this->tmp1 = $Args[0];
                break;
            case 'Tmp3':
                $this->tmp1 = $Args[0];
                break;
            case 'DashedRect':
                $this->DashedRect($Args[0], $Args[1], $Args[2], $Args[3], $Args[4], $Args[5]);
                break;
            default:
                \Log::info('=====unknown instruction======');
                \Log::info($Instruction);
        }
    }

    private function bookmarkEngine($Args, $Data)
    {
        if (is_array($Data)) {
            // extracts the bookmarks into the $bookmarks var
            preg_match_all('/{([^{}]*)}/', $Args, $bookmarks);
            if (count($bookmarks[0]) > 0) {
                // replaces bookmarks
                foreach ($bookmarks[0] as $bookmark) {
                    $Args = $this->handleBookmark($bookmark, $Args, $Data);
                }
            }
        }
        // create an array of matches that are betwen commas but exclude
        // commans that are somewhere within a string ('s)
        preg_match_all('/(\'[^\']*\')|[^,]+/', $Args, $ArgsTmp);
        // only include the first set of matches and make sure the array contains
        // at least one string (later code expects it)
        $ArgsArray = array_pad($ArgsTmp[0], 1, '');

        foreach ($ArgsArray as $key => $Arg) {
            $ArgsArray[$key] = str_replace('\'', '', $Arg);
        }
        return array_pad($ArgsArray, 8, null);
    }

    private function handleBookmark($bookmark, $Args, $Data)
    {
        if (strpos($bookmark, 'GetX+') !== false) {
            $Value = $this->replaceFirst(['{','}','GetX'], '', $bookmark);
            $X = $this->GetX();
            $X = $X == '' ? $Value : $X + strval($Value);
            return $this->replaceFirst($bookmark, $X, $Args);
        } elseif (strpos($bookmark, 'GetX-') !== false) {
            $Value = $this->replaceFirst(['{','}','GetX'], '', $bookmark);
            $X = $this->GetX();
            $X = $X == '' ? $Value : $X + strval($Value);
            return $this->replaceFirst($bookmark, $X, $Args);
        } elseif (strpos($bookmark, 'GetX') !== false) {
            $X = $this->GetX();
            $X = $X == '' ? 0 : $X;
            return $this->replaceFirst($bookmark, $X, $Args);
        } elseif (strpos($bookmark, 'GetY+') !== false) {
            $Value = $this->replaceFirst(['{','}','GetY'], '', $bookmark);
            $Y = $this->GetY();
            $Y = $Y == '' ? $Value : $Y + strval($Value);
            return $this->replaceFirst($bookmark, $Y, $Args);
        } elseif (strpos($bookmark, 'GetY-') !== false) {
            $Value = $this->replaceFirst(['{','}','GetY'], '', $bookmark);
            $Y = $this->GetY();
            $Y = $Y == '' ? $Value : $Y + strval($Value);
            return $this->replaceFirst($bookmark, $Y, $Args);
        } elseif (strpos($bookmark, 'GetY') !== false) {
            $Y = $this->GetY();
            $Y = $Y == '' ? 0 : $Y;
            return $this->replaceFirst($bookmark, $Y, $Args);
        } elseif (strpos($bookmark, 'PageNo') !== false) {
            return $this->replaceFirst($bookmark, $this->PageNo(), $Args);
        } elseif (strpos($bookmark, 'TotalPages') !== false) {
            return $this->replaceFirst($bookmark, '{nb}', $Args);
        } elseif (strpos($bookmark, 'Data->') !== false) {
            return $this->replaceFirst($bookmark, $this->getReportData($bookmark, $Data), $Args);
        } elseif (strpos($bookmark, '{Tmp1}') !== false) {
            $result = is_array($this->tmp1) ? '{Tmp1}' : $this->tmp1;
            return $this->replaceFirst($bookmark, $result, $Args);
        } elseif (strpos($bookmark, '{Tmp2}') !== false) {
            $result = is_array($this->tmp2) ? '{Tmp2}' : $this->tmp1;
            return $this->replaceFirst($bookmark, $result, $Args);
        } elseif (strpos($bookmark, '{Tmp3}') !== false) {
            $result = is_array($this->tmp3) ? '{Tmp3}' : $this->tmp1;
            return $this->replaceFirst($bookmark, $result, $Args);
        } elseif (strpos($bookmark, '{PrescribingProviders}') !== false) {
            $prescribing = $this->getPrescribingProviders($this->ReportData['Injections']);
            return $this->replaceFirst($bookmark, $prescribing, $Args);
        } elseif (strpos($bookmark, '{InjectingProviders}') !== false) {
            $injecting = $this->getInjectingProviders($this->ReportData['Injections']);
            return $this->replaceFirst($bookmark, $injecting, $Args);
        } elseif (strpos($bookmark, '{AttendingProviders}') !== false) {
            $attending = $this->getAttendingProviders($this->ReportData['Injections']);
            return $this->replaceFirst($bookmark, $attending, $Args);
        } elseif (strpos($bookmark, '{MixedBy}') !== false) {
            $mixedBy = $this->getMixBy($this->ReportData['TreatmentSet']);
            return $this->replaceFirst($bookmark, $mixedBy, $Args);
        } elseif (strpos($bookmark, '{LabelOutdate}') !== false) {
            $outdate = $this->getOutdate($this->ReportData['TreatmentSet']);
            return $this->replaceFirst($bookmark, $outdate, $Args);
        } elseif (strpos($bookmark, '{SetName}') !== false) {
            $setname = $this->getSetName($this->ReportData['TreatmentSet']);
            return $this->replaceFirst($bookmark, $setname, $Args);
        } elseif (strpos($bookmark, '{SetSize}') !== false) {
            $setsize = $this->getSetSize($this->ReportData['TreatmentSet']);
            return $this->replaceFirst($bookmark, $setsize, $Args);
        } elseif (strpos($bookmark, '{MixedOn}') !== false) {
            $mixedOn = $this->getMixOn($this->ReportData['TreatmentSet']);
            return $this->replaceFirst($bookmark, $mixedOn, $Args);
        } elseif (strpos($bookmark, '{CurrentDateTime}') !== false) {
            return $this->replaceFirst($bookmark, Carbon::now()->toDateTimeString(), $Args);
        } elseif (strpos($bookmark, '{CurrentDate}') !== false) {
            return $this->replaceFirst($bookmark, Carbon::now()->toDateString(), $Args);
        } else {
            \Log::info('======unhandled bookmark=====');
            \Log::info($bookmark);
            return $Args;
        }
    }

    private function getReportData($bookmark, $Data, $PartialReturn = false)
    {
        $IndexArray = explode('->', str_replace(['{','}'], '', $bookmark));
        //index 0 is always data and refers to ReportData
        unset($IndexArray[0]);
        $newData = $Data;
        foreach ($IndexArray as $Index) {
            $DataName = substr($Index, 0, strpos($Index, '['));
            if ($DataName === '') {
                $DataName = $Index;
                $Idx = null;
            } else {
                $Idx = str_replace([$DataName.'[',']'], '', $Index);
            }
            if (isset($newData[$DataName])) {
                if (!is_null($Idx)) {
                    $newData = isset($newData[$DataName][$Idx]) ? $newData[$DataName][$Idx] : '';
                } else {
                    $newData = isset($newData[$DataName]) ? $newData[$DataName] : '';
                }
            } else {
                break;
            }
        }
        if (isset($newData) && is_array($newData) && !$PartialReturn) {
            // if we didn't get to actual data, just return empty string so nothing
            // explodes
            $newData = '';
        }
        return $newData;
    }

    private function parseColorByName($Name)
    {
        switch (strtoupper($Name)) {
            case 'RED':
                $Color = [255,0,0];
                break;
            case 'BLUE':
                $Color = [0,0,255];
                break;
            case 'GRN':
                $Color = [0,128,0];
                break;
            case 'YLW':
                $Color = [255,255,0];
                break;
            case 'ORNG':
                $Color = [255,165,0];
                break;
            case 'WHT':
                $Color = [255,255,255];
                break;
            case 'LTGR':
                $Color = [144,238,144];
                break;
            case 'LTBL':
                $Color = [173,216,230];
                break;
            case 'SLVR':
                $Color = [192,192,192];
                break;
            case 'PRPL':
                $Color = [128,0,128];
                break;
            case 'PINK':
                $Color = [255,192,203];
                break;
            case 'GOLD':
                $Color = [128,96,0];
                break;
            default:
                $Color = [0,0,0];
                break;
        }

        return $Color;
    }

    private function replaceFirst($search, $replace, $string)
    {
        $search = is_array($search) ? $search : [$search];
        foreach ($search as $value) {
            # code...
            $pos = strpos($string, $value);
            if ($pos !== false) {
                $string = substr_replace($string, $replace, $pos, strlen($value));
            }
        }
        return $string;
    }
}
