<?php
/**
 * @SWG\Swagger(
 *     swagger="3.0",
 *     schemes={"https"},
 *     host="$$Host",
 *     basePath="$$BasePath",
 *     consumes={
 *        "application/json"
 *     },
*      produces={
 *        "application/json"
 *     },
 *     @SWG\Info(
 *         version="$$Version.0.0",
 *         title="Xtract API",
 *         description="Swagger documentation for Xtract API v1.0
   An * indicates that it should have accurate info",
 *         termsOfService="http://helloreverb.com/terms/",
 *         @SWG\Contact(
 *             email="andrew@xtractsolutions.com"
 *         ),
 *         @SWG\License(
 *             name="License????",
 *             url="http://www.xtractsolutions.com/"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Xtract Solutions",
 *         url="http://www.xtractsolutions.com"
 *     )
 * )
 * @SWG\SecurityScheme(
 *   securityDefinition="xtract_auth",
 *   type="oauth2",
 *   flow="password",
 *   tokenUrl="https://$$AuthPath/oauth/token",
 * )
 */
