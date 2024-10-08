<?php

if (! function_exists('rootEnv')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param mixed|null $default
     * @return mixed
     */
    function rootEnv(string $key, mixed $default = null): mixed
    {
        $envPath = base_path('.env');

        $content = file_exists($envPath) ? file_get_contents($envPath) : '';

        preg_match( "/$key=(.*?)\n/", $content, $matches);

        return $matches[1] ?? $default;
    }
}

if (! function_exists('pathByNamespace')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $needle
     * @return string|null
     */
    function pathByNamespace(string $needle): ?string
    {
        $composerFile = base_path('composer.json');

        $content = json_decode(file_exists($composerFile) ? file_get_contents($composerFile) : '{}', true);

        if ($content && isset($content['autoload']) && isset($content['autoload']['psr-4'])) {
            foreach ($content['autoload']['psr-4'] as $namespace => $path) {
                if (str_ends_with($namespace, "$needle\\")) {
                    return $path;
                }
            }
        }

        return null;
    }
}