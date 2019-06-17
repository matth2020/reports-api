<?php
namespace App\BusinessLogic\Reports;

use App\BusinessLogic\Reports\FPDF\tFPDF;
use App\Models\Xisprefs;
use Carbon\Carbon;

class XtractReport extends tFPDF
{
    private $LastFont = 'Arial';
    private $LastStyle = '';
    private $LastSize = 10;

    public function __construct($Template)
    {
        $Orientation = $Template->file_info->orientation;
        $Size = $Template->file_info->paper_size;
        $Units = $Template->file_info->units;
        parent::__construct($Orientation, $Units, $Size);
    }

    public function SetFont($family, $style = '', $size = 0)
    {
        $this->LastFont = $family;
        $this->LastStyle = $style;
        $this->LastSize = $size;
        parent::SetFont($family, $style, $size);
    }

    public function SetFontSize($size)
    {
        $this->LastSize = $size;
        parent::SetFontSize($size);
    }

    protected function fontAwesomeIcon($library, $code, $size = 15)
    {
        $Font = '';
        switch ($library) {
            case 'fas':
                $Font = 'FontAwesome5ProSolid';
                break;
            case 'far':
                $Font = 'FontAwesome5ProRegular';
                break;
            case 'fal':
                $Font = 'FontAwesome5ProLight';
                break;
            case 'fab':
                $Font = 'FontAwesome5ProBrand';
                break;
            default:
                return;
        }
        $this->AddFont($Font, '', $Font.'.ttf', true);
        $this->SetFont($Font, '', $size);
        $this->Write($size/2, json_decode('"\u'.$code.'"'));
        // return font to default
        $this->setFont($this->LastFont, $this->LastStyle, $this->LastSize);
    }

    public function getAttendingProviders($Injections)
    {
        $Attending = [];
        if (sizeOf($Injections) < 1) {
            return;
        }
        foreach ($Injections as $Injection) {
            if (isset($Injection['attending'])) {
                array_push($Attending, $Injection['attending']);
            }
        }
        return implode(',', $Attending);
    }

    public function getMixBy($TreatmentSet)
    {
        if (!isset($TreatmentSet) || sizeOf($TreatmentSet['vials']) < 1 || sizeOf($TreatmentSet['vials'][0]['vials']) < 1) {
            return;
        }
        return $TreatmentSet['vials'][0]['vials'][0]['user']['displayname'];
    }

    public function getMixOn($TreatmentSet)
    {
        if (!isset($TreatmentSet) || sizeOf($TreatmentSet['vials']) < 1 || sizeOf($TreatmentSet['vials'][0]['vials']) < 1) {
            return;
        }
        return $TreatmentSet['vials'][0]['vials'][0]['mix_date'];
    }

    public function getOutdate($TreatmentSet)
    {
        if (!isset($TreatmentSet) || sizeOf($TreatmentSet['vials']) < 1 || sizeOf($TreatmentSet['vials'][0]['vials']) < 1) {
            return;
        }
        return $TreatmentSet['vials'][0]['vials'][0]['label_out_date'];
    }

    public function getSetName($TreatmentSet)
    {
        if (!isset($TreatmentSet) || sizeOf($TreatmentSet['vials']) < 1) {
            return;
        }
        return $TreatmentSet['vials'][0]['name'];
    }

    public function getSetSize($TreatmentSet)
    {
        if (!isset($TreatmentSet) || sizeOf($TreatmentSet['vials']) < 1) {
            return;
        }
        return $TreatmentSet['vials'][0]['size'];
    }

    public function getInjectingProviders($Injections)
    {
        $Injecting = [];
        if (sizeOf($Injections) < 1) {
            return;
        }
        foreach ($Injections as $Injection) {
            array_push($Injecting, $Injection['user']['displayname']);
        }
        return implode(',', $Injecting);
    }

    public function getPrescribingProviders($Injections)
    {
        $Prescribing = [];
        if (sizeOf($Injections) < 1) {
            return;
        }
        foreach ($Injections as $Injection) {
            $Provider = $Injection['compound']['prescription']['provider'];
            array_push($Prescribing, $Provider['first'].' '.$Provider['mi'].'. '.$Provider['last'].', '.$Provider['suffix']);
        }
        return implode(',', $Prescribing);
    }

    public function getLocals($Injections)
    {
        $Reactions = $this->getReactionNames();
        $ReactionsL = [];
        foreach ($Injections as $Injection) {
            if ($Injection['systemic_reaction'] === $Reactions->systemic[0] && $Injection['local_reaction'] !== $Reactions->local[0]) {
                array_push($ReactionsL, $Injection);
            }
        }
        return $ReactionsL;
    }
    public function getSystemics($Injections)
    {
        $Reactions = $this->getReactionNames();
        $ReactionsS = [];
        foreach ($Injections as $Injection) {
            if ($Injection['systemic_reaction'] !== $Reactions->systemic[0]) {
                array_push($ReactionsS, $Injection);
            }
        }
        return $ReactionsS;
    }

    public function finish()
    {
        $this->AliasNbPages();
        $this->close();
    }

    private static function getReactionNames()
    {
        $ReactionStrings = Xisprefs::firstOrFail();
        $LocalNames = explode(',', $ReactionStrings->reactNamesL);
        $SystemicNames = explode(',', $ReactionStrings->reactNamesS);
        $Reactions = app()->make('stdClass');
        $Reactions->systemic = $SystemicNames;
        $Reactions->local = $LocalNames;

        return $Reactions;
    }

    public function Circle($x, $y, $r, $style = 'D')
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    public function Ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if ($style=='F') {
            $op='f';
        } elseif ($style=='FD' || $style=='DF') {
            $op='B';
        } else {
            $op='S';
        }
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf(
            '%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,
            ($h-$y)*$k,
            ($x+$rx)*$k,
            ($h-($y-$ly))*$k,
            ($x+$lx)*$k,
            ($h-($y-$ry))*$k,
            $x*$k,
            ($h-($y-$ry))*$k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,
            ($h-($y-$ry))*$k,
            ($x-$rx)*$k,
            ($h-($y-$ly))*$k,
            ($x-$rx)*$k,
            ($h-$y)*$k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,
            ($h-($y+$ly))*$k,
            ($x-$lx)*$k,
            ($h-($y+$ry))*$k,
            $x*$k,
            ($h-($y+$ry))*$k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x+$lx)*$k,
            ($h-($y+$ry))*$k,
            ($x+$rx)*$k,
            ($h-($y+$ly))*$k,
            ($x+$rx)*$k,
            ($h-$y)*$k,
            $op
        ));
    }

    /*
    Author     : Antoine Michéa
    Web        : saturn-share.org
    Program    : dashed_rect.php
    License    : GPL v2
    Description: Allows to draw a dashed rectangle. Parameters are:
                 x1, y1 : upper left corner of the rectangle.
                 x2, y2 : lower right corner of the rectangle.
                 width  : dash thickness (1 by default).
                 nb     : number of dashes per line (15 by default).
    Date       : 2003-01-07
    */
    public function DashedRect($x1, $y1, $x2, $y2, $width = 1, $nb = 15)
    {
        $this->SetLineWidth($width);
        $longueur=abs($x1-$x2);
        $hauteur=abs($y1-$y2);
        if ($longueur>$hauteur) {
            $Pointilles=($longueur/$nb)/2; // length of dashes
        } else {
            $Pointilles=($hauteur/$nb)/2;
        }
        for ($i=$x1; $i<=$x2; $i+=$Pointilles+$Pointilles) {
            for ($j=$i; $j<=($i+$Pointilles); $j++) {
                if ($j<=($x2-1)) {
                    $this->Line($j, $y1, $j+1, $y1); // upper dashes
                    $this->Line($j, $y2, $j+1, $y2); // lower dashes
                }
            }
        }
        for ($i=$y1; $i<=$y2; $i+=$Pointilles+$Pointilles) {
            for ($j=$i; $j<=($i+$Pointilles); $j++) {
                if ($j<=($y2-1)) {
                    $this->Line($x1, $j, $x1, $j+1); // left dashes
                    $this->Line($x2, $j, $x2, $j+1); // right dashes
                }
            }
        }
    }
}
