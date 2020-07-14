<?php declare(strict_types = 1);
// src/IServ/EximStatsBundle/Controller/DefaultController.php
namespace Stsbl\EximStatsBundle\Controller;

use IServ\CoreBundle\Controller\AbstractPageController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * The MIT License
 *
 * Copyright 2020 Felix Jacobi.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @Route("/admin/eximstats")
 */
class DefaultController extends AbstractPageController
{
    const BASE_PATH = '/var/www/eximstats';

    /**
     * @Route("/display/{page}", name="admin_eximstats_display", requirements={"page"=".+"})
     * @Route("/display", name="admin_eximstats_display_index")
     *
     * @param Request $request
     * @param string $page
     * @return Response
     */
    public function displayAction(Request $request, string $page = null): Response
    {
        $path = self::BASE_PATH;
        if (null !== $page) {
            $path .= '/' . $page;
        }

        $file = new File($path, false);

        if ($file->isDir()) {
            $file = new File($file->getRealPath() . '/index.html');
            if (!$file->isFile()) {
                return new Response("<body><p>" . _('There are no statistics available yet.') . "</p></body>");
            }
        }

        if (null === $page) {
            return $this->redirect($request->getUri(). '/index.html');
        }

        if (!$file->isFile()) {
            throw $this->createNotFoundException('Requested page was not found.');
        }

        if (strpos($file->getRealPath(), self::BASE_PATH) !== 0) {
            throw $this->createAccessDeniedException(
                'You are not allowed to access files outside of /var/www/eximstats directory.'
            );
        }

        $response = new Response();
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->setContent(file_get_contents($file->getRealPath()));

        return $response;
    }

    /**
     * @Route("", name="admin_eximstats_index")
     * @Template("@StsblEximStats/Default/index.html.twig")
     * @return array
     */
    public function indexAction(): array
    {
        $this->addBreadcrumb(_('E-Mail server statistics'));

        return [];
    }
}
