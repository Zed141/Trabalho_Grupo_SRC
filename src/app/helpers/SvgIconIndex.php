<?php

namespace app\helpers;

/**
 *
 */
final class SvgIconIndex {

    public const string PLUS = 'PLUS';
    public const string CHECK = 'CHECK';
    public const string KEY = 'KEY';
    public const string HOME = 'HOME';
    public const string SHIELD = 'SHIELD';
    public const string SEARCH = 'SEARCH';
    public const string BELL = 'BELL';
    public const string SHARE = 'SHARE';
    public const string EDIT = 'EDIT';
    public const string EYE = 'EYE';
    public const string EXTERNAL_LINK = 'EXTERNAL_LINK';
    public const string INFO = 'INFO';

    /**
     * @param string $index
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public static function icon(string $index, int $width = 24, int $height = 24): string {
        return match ($index) {
            self::BELL => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></svg>',
            self::PLUS => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>',
            self::CHECK => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>',
            self::KEY => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /><path d="M15 9h.01" /></svg>',
            self::HOME => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>',
            self::SHIELD => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M12 12l0 2.5"/></svg>',
            self::SEARCH => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/><path d="M21 21l-6 -6"/></svg>',
            self::SHARE => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 6m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M8.7 10.7l6.6 -3.4" /><path d="M8.7 13.3l6.6 3.4" /></svg>',
            self::EDIT => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>',
            self::EYE => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>',
            self::EXTERNAL_LINK => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>',
            self::INFO => '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="' . $width . '" height="' . $height . '" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path><path d="M12 9h.01"></path><path d="M11 12h1v4h1"></path></svg>',
            default => ''
        };
    }
}